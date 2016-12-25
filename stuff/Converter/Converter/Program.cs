using System;
using System.IO;
using System.Collections;
using System.Collections.Generic;
using System.Linq;

namespace Converter
{
	class MainClass
	{
		public static void Main (string[] args)
		{
			string inFile = Path.Combine (Environment.GetFolderPath (Environment.SpecialFolder.Desktop), "input.txt");
			string outFile = Path.Combine (Environment.GetFolderPath (Environment.SpecialFolder.Desktop), "output.txt");
			var lines = File.ReadLines (inFile);
			var result = new List<string[]>();
			const int count = 5;

			foreach (string line in lines) {
				string[] parts = line.Split (new char[] { ' ', ' ', '\t' }, count);
				result.Add (parts);
			}

			List<string>[] result2 = new List<string>[count];
			for (int i = 0; i < count; ++i) {
				result2[i] = new List<string> ();
			}
 
			for (int i = 0; i < count; ++i) {
				for (int j = 0; j < result.Count; ++j) {
					result2 [i].Add ("\'" + result [j] [i] + "\'");
				}
			}

			string result3 = "";
			for (int i = 0; i < count; ++i) {
				result3 += '[' + string.Join (", ", result2 [i]) + "],\r\n";
			}

			File.WriteAllText (outFile, result3);
		}
	}
}
