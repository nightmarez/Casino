using System;
using System.Drawing;

namespace SpriteSheeter
{
	class MainClass
	{
		public static void Main (string[] args)
		{
			string[] fileNames = new[] {
				@"/home/nightmarez/Desktop/html/common/imgs/b_gamble.png",
				@"/home/nightmarez/Desktop/html/common/imgs/b_gamble_black.png",
				@"/home/nightmarez/Desktop/html/common/imgs/b_gamble_red.png",
				@"/home/nightmarez/Desktop/html/common/imgs/b_paytable.png",
				@"/home/nightmarez/Desktop/html/common/imgs/b_skip.png",
				@"/home/nightmarez/Desktop/html/common/imgs/b_start.png",
			};

			foreach (string fileName in fileNames) {
				var bmp1 = (Bitmap)Image.FromFile (fileName);
				using (var bmp2 = new Bitmap (bmp1.Width * 4, bmp1.Height / 4, bmp1.PixelFormat)) {
					for (int k = 0; k < 4; ++k)
						for (int i = 0; i < bmp1.Width; ++i)
							for (int j = 0; j < bmp1.Height / 4; ++j)
								bmp2.SetPixel (i + k * bmp1.Width, j, bmp1.GetPixel (i, j + k * bmp1.Height / 4));
					bmp1.Dispose ();
					bmp2.Save (fileName);
				}
			}
		}
	}
}
