using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;

namespace Extractor
{
	public static class MemoryStreamExt
	{
		public static string[] GetLastBytes(this MemoryStream stream, int count)
		{
			if (count > (int)stream.Length)
				return new string[0];

			var buffer = new byte[count];
			stream.Seek (-count, SeekOrigin.End);
			stream.Read (buffer, 0, count);
			return BitConverter.ToString (buffer).ToUpperInvariant ().Split(new char[] {'-'});
		}
	}

	class MainClass
	{
		enum State {
			Walk,
			Fill,
			Collect
		}

		private static bool CompareEtalons(string[] a, string[] b)
		{
			if (a.Length != b.Length)
				return false;

			for (int i = 0; i < a.Length; ++i)
				if (a [i] != b [i])
					return false;

			return true;
		}

		public static void Main (string[] args)
		{
			if (args.Length < 2)
				return;

			string fileName = args[0];
			string outputDirectory = args[1];

			var etalons = new[] {
				new KeyValuePair<string, string[][]> (
					"png",
					new[] {
						new[] { "89", "50", "4E", "47", "0D", "0A", "1A", "0A" },
						new[] { "00", "00", "00", "00", "49", "45", "4E", "44", "AE", "42", "60", "82" }
					}
				),

				new KeyValuePair<string, string[][]> (
					"jpg",
					new[] {
						new[] { "FF", "D8", "FF", /*"E0"*/ },
						new[] { "FF", "D9" }
					}
				),

				new KeyValuePair<string, string[][]> (
					"gif",
					new[] {
						new[] { "47", "49", "46", "38", "37", "61" },
						new[] { "00", "3B" }
					}
				),
				new KeyValuePair<string, string[][]> (
					"gif",
					new[] {
						new[] { "47", "49", "46", "38", "39", "61" },
						new[] { "00", "3B" }
					}
				),






				/*
				new KeyValuePair<string, string[][]> (
					"swf",
					new[] {
						new[] { "43", "57", "53" },
						new[] { "00", "00", "00", "00", "49", "45", "4E", "44", "AE", "42", "60", "82" }
					}
				),
				new KeyValuePair<string, string[][]> (
					"swf",
					new[] {
						new[] { "46", "57", "53" },
						new[] { "00", "00", "00", "00", "49", "45", "4E", "44", "AE", "42", "60", "82" }
					}
				),
				new KeyValuePair<string, string[][]> (
					"swf",
					new[] {
						new[] { "5A", "57", "53" },
						new[] { "00", "00", "00", "00", "49", "45", "4E", "44", "AE", "42", "60", "82" }
					}
				),
				*/
			};

			using (var fs = new FileStream (fileName, FileMode.Open)) {
				int b, i = 0;
				var state = State.Walk;
				int counter = 0;
				var memory = new MemoryStream((int)fs.Length);
				List<KeyValuePair<string, string[][]>> ranges = null;
				var start = DateTime.Now;
				var oldTime = DateTime.Now;
				int oldPos = 0;
				int bps = 0;
				int currBps = 0;
				int oldPercents = 0;

				while ((b = fs.ReadByte ()) >= 0) {
					Console.SetCursorPosition (0, Console.CursorTop);
					int percents = (int) Math.Floor ((fs.Position * 100 + 1) / (double)fs.Length * 100);

					if (percents != oldPercents) {
						oldPercents = percents;
						percents = (int)Math.Floor (percents / 100.0);

						if ((DateTime.Now - oldTime).TotalMilliseconds >= 1000) {
							oldTime = DateTime.Now;
							currBps = bps;
							bps = 0;
							oldPos = (int)fs.Position;
						} else {
							bps += (int)fs.Position - oldPos;
							oldPos = (int)fs.Position;
						}

						if (percents == 0) {
							Console.Write("{0} / {1}  [{2}%, bps: {3}]", fs.Position, fs.Length, percents, currBps);
						} else {
							var estimated = TimeSpan.FromMilliseconds ((DateTime.Now - start).TotalMilliseconds / percents * (100 - percents));
							var elapsed = TimeSpan.FromMilliseconds ((DateTime.Now - start).TotalMilliseconds);

							Console.Write("{0} / {1}  [{2}%, estimated: {3}, elapsed: {4}, bps: {5}]", 
								fs.Position, 
								fs.Length, 
								percents, 
								estimated.ToString ("hh\\:mm\\:ss"),
								elapsed.ToString ("hh\\:mm\\:ss"),
								currBps);
						}
					}

					string hex = BitConverter.ToString (new[] { (byte)b }).ToUpperInvariant();

					switch (state) {
					case State.Walk:
						ranges = etalons
							.Where (kvp => kvp.Value [0] [0] == hex).ToList ();

						if (ranges.Count > 0) {
							memory.WriteByte ((byte)b);
							state = State.Fill;
							i = 1;
						}
						break;

					case State.Fill:
						var filled = ranges
							.Where (kvp => kvp.Value [0].Length == i + 1 && kvp.Value [0] [i] == hex).ToList ();

						if (filled.Count > 0) {
							ranges = filled;
							memory.WriteByte ((byte)b);
							state = State.Collect;
						} else {
							ranges = ranges
								.Where (kvp => kvp.Value [0].Length > i && kvp.Value [0] [i] == hex).ToList ();

							if (ranges.Count == 0) {
								memory.Close ();
								memory.Dispose ();
								memory = new MemoryStream ((int)fs.Length);
								state = State.Walk;
								goto case State.Walk;
							} else {
								memory.WriteByte ((byte)b);
								++i;
							}
						}

						break;

					case State.Collect:
						var finished = ranges
							.Where (
								kvp => kvp.Value [1].Length <= memory.Length &&
							    CompareEtalons (kvp.Value [1], memory.GetLastBytes (kvp.Value [1].Length)));

						if (finished.Count () > 0) {
							foreach (var finish in finished) {
								string outFileName = (counter++) + "." + finish.Key;

								Console.WriteLine ();
								Console.WriteLine (outFileName);
									
								using (var of = new FileStream (Path.Combine (outputDirectory, outFileName), FileMode.Create)) {
									memory.WriteTo(of);
									of.Flush ();
									of.Close ();
								}
							}

							memory.Close ();
							memory.Dispose ();
							memory = new MemoryStream ((int)fs.Length);
							state = State.Walk;
							goto case State.Walk;
						} else {
							memory.WriteByte ((byte)b);
						}

						break;
					}
				}
			}
		}
	}
}
