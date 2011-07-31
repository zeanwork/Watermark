<?php
/**
 * Apply watermark image example
 * http://github.com/josemarluedke/Watermark/apply
 * 
 * Copyright 2011, Josemar Davi Luedke <josemarluedke@gmail.com>
 * 
 * Licensed under the MIT license
 * Redistributions of part of code must retain the above copyright notice.
 * 
 * @author Josemar Davi Luedke <josemarluedke@gmail.com>
 * @version 0.1.1
 * @copyright Copyright 2010, Josemar Davi Luedke <josemarluedke.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */


# Include watermark class
require "../watermark.php";

# Watermark class started
$watermark = new Watermark();

# Apply watermark
$watermark->apply('from.jpg', 'to.jpg', 'watermark.png', 3);

# Parameters of method apply
# 1: From image, original image
# 2: Target image, image destination
# 3: Watermark image
# 4: Watermark position number
# 		 * 0: Centered
#		 * 1: Top Left
#		 * 2: Top Right
#		 * 3: Footer Right
#		 * 4: Footer left
#		 * 5: Top Centered
#		 * 6: Center Right
#		 * 7: Footer Centered
#		 * 8: Center Left

?>

Original image:
<br />
<img src="from.jpg" />

<br />
Watermark:
<br />
<img src="watermark.png" />

<br />
Watermark applied:
<br />
<img src="to.jpg" />