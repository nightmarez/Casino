using System;
using System.Drawing;
using System.Drawing.Imaging;

namespace SpriteSheeter
{
	class MainClass
	{
		public static void Main (string[] args)
		{
			string[] fileNames = new[] {
				@"/home/nightmarez/Desktop/dialogbox.jpg",
				@"/home/nightmarez/Desktop/dialogbox1"
			};

			foreach (string fileName in fileNames) {
				var bmp1 = (Bitmap)Image.FromFile (fileName);
				using (var bmp2 = new Bitmap (bmp1.Width, bmp1.Height / 2, PixelFormat.Format32bppArgb)) {
					for (int i = 0; i < bmp1.Width; ++i)
						for (int j = 0; j < bmp1.Height / 2; ++j) {
							var pixel = bmp1.GetPixel (i, j);
							var alpha = bmp1.GetPixel (i, j + bmp1.Height / 2).R;
							pixel = Color.FromArgb (alpha, pixel.R, pixel.G, pixel.B);
							bmp2.SetPixel (i, j, pixel);
						}
					bmp1.Dispose ();
					bmp2.Save (fileName + ".png");
				}
			}
		}
	}
}
