<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<title>Meziefields Investment Services Limited  | Contact Us</title>

<!-- Stylesheets -->
<?php require_once "css.php"; ?>

</head>

<body>

<div class="page-wrapper">
 	
   <?php require_once "header.php"; 
	$notification = $bon->ContactUs(); ?>

	<!-- Page Title -->
    <section class="page-title" style="background-image: url(images/background/3.jpg)">
    	<div class="auto-container">
			<h1>Contact Us </h1>
			<ul class="page-breadcrumb">
				<li><a href="index.php">home</a></li>
				<li>Contact Us</li>
			</ul>
        </div>
    </section>
    <!-- End Page Title -->
	
	<!-- Contact Page Section -->
    <section class="contact-page-section">
		<div class="auto-container">
			<div class="row clearfix">
				
				<!-- Form Column -->
				<div class="form-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<!-- Title Box -->
						<div class="title-box">
							<h3>Get in touch</h3>
							<div class="text">There are many variations of passages of Lorem Ipsum <br> but the majority have suffered.</div>
						</div>
						<?php echo $notification; ?>
						<!-- Default Form -->
						<div class="default-form contact-form">
							<form method="post" name = "contact_form" action="" id="contact-form">
								<div class="form-group">
									<input type="text" name="name" value="" placeholder="Name" required>
								</div>
								
								<div class="form-group">
									<input type="email" name="email" value="" placeholder="Email" required>
								</div>
								
								<div class="form-group">
									<input type="text" name="subject" value="" placeholder="Subject" required>
								</div>
								
								<div class="form-group">
									<textarea name="message" placeholder="Your Message"></textarea>
								</div>
								
								<div class="form-group g-recaptcha" data-sitekey="6LfbZcoZAAAAADLXYo667UQSsSyq1idHJsOvfRFZ"></div>
								
								<div class="form-group text-center">
									<button type="submit" class="theme-btn btn-style-four"><span class="txt">Send Now</span></button>
								</div>
								<input type = 'hidden' name = 'contact_form'/>
							</form>
						</div>
						<!--End Default Form-->
						
					</div>
				</div>
				
				<!-- Info Column -->
				<div class="info-column col-lg-6 col-md-12 col-sm-12">
					<div class="inner-column">
						<!-- Title Box -->
						<div class="title-box">
							<h3>Contact Information</h3>
							<div class="text">There are many variations of passages of Lorem Ipsum <br> but the majority have suffered.</div>
						</div>
						
						<ul class="contact-info">
							<li>
								<span class="icon flaticon-telephone"></span> 
								<strong>Phone :</strong>
								<a href="tel:+234 803 340 1735">+234 803 340 1735</a>,
								<a href="tel:+234 802 344 9725">+234 802 344 9725</a>
							</li>

							<li>
								<span class="icon flaticon-email-4"></span>
								<strong>Email :</strong>
								<a href="mailto:info@meziefields.com">info@meziefields.com</a>
							</li>

							<li>
								<span class="icon flaticon-maps-and-flags"></span>
								<strong>Head Office:</strong> #09 Obinali Close, Abayi-Aba Abia State. <br><br>
								<strong>Other Address:</strong> #03 Road 2, God’s City Estate, Ozuoba Port Harcourt, Rivers State

								
							</li>
						</ul>
						
					</div>
				</div>
				
			</div>
		</div>
	</section>
	<!-- End Contact Page Section -->
	
	<!-- Contact Map Section -->
    <section class="map-section">
        <div class="outer-container">
            <!-- Social Outer -->
            <div class="map-outer">
                <div class="map-canvas"
                    data-zoom="12"
                    data-lat="-37.817085"
                    data-lng="144.955631"
                    data-type="roadmap"
                    data-hue="#ffc400"
                    data-title="Envato"
                    data-icon-path="images/icons/map-marker.png"
                    data-content="Melbourne VIC 3000, Australia<br><a href='mailto:info@youremail.com'>info@youremail.com</a>">
                </div>
				
            </div>
        </div>
    </section>
    <!-- Contact Map Section -->
	

	<!-- Main Footer -->
    <?php require_once "footer.php"; ?>
	<!-- End Main Footer -->
	
</div>
<!--End pagewrapper-->



<?php require_once "js.php"; ?>

</body>
</html>