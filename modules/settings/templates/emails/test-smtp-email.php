<?php
/**
 * Test SMTP email.
 *
 * Tempalte generated using https://stripo.email/
 *
 * @package Welcome_Email_Editor
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<!DOCTYPE html
		PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" lang="en">

<head>
	<meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1" name="viewport">
	<meta name="x-apple-disable-message-reformatting">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="telephone=no" name="format-detection">
	<title>Swift SMTP - Test SMTP Email</title>

	<!--[if (mso 16)]>
	<style type="text/css">
		a {
			text-decoration: none;
		}
	</style>
	<![endif]-->

	<!--[if gte mso 9]>
	<style>sup {
		font-size: 100% !important;
	}</style>
	<![endif]-->

	<!--[if gte mso 9]>
	<xml>
		<o:OfficeDocumentSettings>
			<o:AllowPNG></o:AllowPNG>
			<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml>
	<![endif]-->

	<!--[if !mso]>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet">
	<!--<![endif]-->

	<style type="text/css">
		.rollover:hover .rollover-first {
			max-height: 0px !important;
			display: none !important;
		}

		.rollover:hover .rollover-second {
			max-height: none !important;
			display: block !important;
		}

		#outlook a {
			padding: 0;
		}

		.es-button {
			mso-style-priority: 100 !important;
			text-decoration: none !important;
		}

		a[x-apple-data-detectors] {
			color: inherit !important;
			text-decoration: none !important;
			font-size: inherit !important;
			font-family: inherit !important;
			font-weight: inherit !important;
			line-height: inherit !important;
		}

		.ssmtp-bg-blue {
			background-color: #0d3c79;
		}

		.ssmtp-bg-pink {
			background-color: #FE337C;
		}

		.ssmtp-container {
			width: 600px;
			max-width: 100% !important;
		}

		.ssmtp-logo {
			width: 128px;
		}

		.ssmtp-button {

		}

		.ssmtp-signature {
			width: 90px;
		}

		@media only screen and (max-width: 576px) {
			p,
			ul li,
			ol li,
			a {
				line-height: 150% !important
			}

			h1,
			h2,
			h3,
			h1 a,
			h2 a,
			h3 a {
				line-height: 120% !important
			}

			h1 {
				font-size: 30px !important;
				text-align: center
			}

			h2 {
				font-size: 26px !important;
				text-align: center
			}

			h3 {
				font-size: 20px !important;
				text-align: center
			}

			h1 a {
				text-align: center
			}

			.es-header-body h1 a,
			.es-content-body h1 a,
			.es-footer-body h1 a {
				font-size: 30px !important
			}

			h2 a {
				text-align: center
			}

			.es-header-body h2 a,
			.es-content-body h2 a,
			.es-footer-body h2 a {
				font-size: 26px !important
			}

			h3 a {
				text-align: center
			}

			.es-header-body h3 a,
			.es-content-body h3 a,
			.es-footer-body h3 a {
				font-size: 20px !important
			}

			.es-menu td a {
				font-size: 14px !important
			}

			.es-header-body p,
			.es-header-body ul li,
			.es-header-body ol li,
			.es-header-body a {
				font-size: 16px !important
			}

			.es-content-body p,
			.es-content-body ul li,
			.es-content-body ol li,
			.es-content-body a {
				font-size: 16px !important
			}

			.es-footer-body p,
			.es-footer-body ul li,
			.es-footer-body ol li,
			.es-footer-body a {
				font-size: 14px !important
			}

			.es-infoblock p,
			.es-infoblock ul li,
			.es-infoblock ol li,
			.es-infoblock a {
				font-size: 12px !important
			}

			*[class="gmail-fix"] {
				display: none !important
			}

			.es-m-txt-c,
			.es-m-txt-c h1,
			.es-m-txt-c h2,
			.es-m-txt-c h3 {
				text-align: center !important
			}

			.es-m-txt-r,
			.es-m-txt-r h1,
			.es-m-txt-r h2,
			.es-m-txt-r h3 {
				text-align: right !important
			}

			.es-m-txt-l,
			.es-m-txt-l h1,
			.es-m-txt-l h2,
			.es-m-txt-l h3 {
				text-align: left !important
			}

			.es-m-txt-r img,
			.es-m-txt-c img,
			.es-m-txt-l img {
				display: inline !important
			}

			a.es-button,
			button.es-button {
				font-size: 16px !important;
				display: block !important;
				max-width: 100%;
				border-left-width: 0px !important;
				border-right-width: 0px !important
			}

			.es-adaptive table,
			.es-left,
			.es-right {
				width: 100% !important
			}

			.es-content table,
			.es-header table,
			.es-footer table,
			.es-content,
			.es-footer,
			.es-header {
				width: 100% !important;
				max-width: 600px !important
			}

			.ssmtp-img {
				max-width: 100%;
				height: auto;
			}

			.esd-block-html table {
				width: auto !important
			}

			table.es-social {
				display: inline-block !important
			}

			table.es-social td {
				display: inline-block !important
			}
		}
	</style>
</head>

<body data-new-gr-c-s-check-loaded="14.1021.0" data-gr-ext-installed
		style="width:100%;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
<div dir="ltr" class="es-wrapper-color" lang="en" style="background-color:#FFFFFF">
	<!--[if gte mso 9]>
	<v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
		<v:fill type="tile" color="#ffffff" origin="0.5, 0" position="0.5, 0"></v:fill>
	</v:background>
	<![endif]-->

	<table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0"
			style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;background-color:#FFFFFF">
		<tr>
			<td valign="top" style="padding:0;Margin:0">
				<table cellpadding="0" cellspacing="0" class="es-header" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
					<tr>
						<td align="center" bgcolor="#0d3c79" style="padding:0;Margin:0;background-color:#0d3c79">
							<table class="es-header-body ssmtp-container" align="center" cellpadding="0" cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
								<tr>
									<td align="left"
										style="Margin:0;padding-top:10px;padding-bottom:15px;padding-left:30px;padding-right:30px">
										<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
											<tr>
												<td align="center" valign="top" style="padding:0;Margin:0;width:540px">
													<table cellpadding="0" cellspacing="0" width="100%"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
														<tr>
															<td align="center" style="padding:0;Margin:0;font-size:0px">
																<a target="_blank"
																	href="https://wordpress.org/plugins/welcome-email-editor/"
																	style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#1376C8;font-size:14px"><img
																			class="ssmtp-img ssmtp-logo"
																			src="https://ps.w.org/welcome-email-editor/assets/icon-256x256.png?rev=3015931"
																			alt="Swift SMTP"
																			style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
																			width="128" title="Swift SMTP">
																</a>
															</td>
														</tr>
														<tr>
															<td align="center" style="padding:0;Margin:0">
																<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#f0f8ff;font-size:16px">
																	CONGRATULATIONS
																</p>
															</td>
														</tr>
														<tr>
															<td align="center" class="es-m-txt-c"
																style="padding:0;Margin:0;padding-bottom:10px">
																<h1
																		style="Margin:0;line-height:38px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:32px;font-style:normal;font-weight:bold;color:#ffffff">
																	Test email was sent successfully
																</h1>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" class="es-content" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
					<tr>
						<td align="center" style="padding:0;Margin:0">
							<table bgcolor="transparent" class="es-content-body ssmtp-container" align="center"
									cellpadding="0"
									cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
								<tr>
									<td align="left"
										style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:40px">
										<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
											<tr>
												<td align="center" valign="top" style="padding:0;Margin:0;width:560px">
													<table cellpadding="0" cellspacing="0" width="100%"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
														<tr>
															<td align="left" class="es-m-txt-c"
																style="padding:0;Margin:0;padding-top:5px;padding-bottom:5px">
																<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#131313;font-size:16px">
																	Thank you for checking out Swift SMTP! We're here to
																	offer a straightforward SMTP setup - for free. Thank
																	you
																	for being with us.
																</p>
																<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#131313;font-size:16px">
																	<br>
																</p>
																<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:24px;color:#131313;font-size:16px">
																	Do you enjoy Swift SMTP? Please consider leaving a
																	review in
																	the official <a target="_blank"
																					href="https://wordpress.org/plugins/welcome-email-editor/"
																					style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:16px">WordPress
																		directory</a> and help us spread the word!</p>
															</td>
														</tr>
														<tr>
															<td align="center"
																style="Margin:0;padding-left:10px;padding-right:10px;padding-top:30px;padding-bottom:20px">
																<a href="https://wordpress.org/support/plugin/welcome-email-editor/reviews/#new-post"
																	class="es-button es-button-1625643206450"
																	target="_blank"
																	style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:16px;padding:15px 25px;display:inline-block;background:#FE337C;border-radius:4px;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-weight:normal;font-style:normal;line-height:19px;max-width:100%;text-align:center;mso-padding-alt:0;mso-border-alt:10px solid #FE337C">
																	Leave a Review
																</a>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" class="es-content" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
					<tr>
						<td align="center" style="padding:0;Margin:0">
							<table bgcolor="#ffffff" class="es-content-body ssmtp-container" align="center"
									cellpadding="0"
									cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
								<tr>
									<td align="left"
										style="padding:0;Margin:0;padding-top:20px;padding-left:20px;padding-right:20px">
										<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
											<tr>
												<td align="center" valign="top" style="padding:0;Margin:0;width:560px">
													<table cellpadding="0" cellspacing="0" width="100%"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
														<tr>
															<td align="center" style="padding:0;Margin:0;font-size:0px">
																<a target="_blank" href="https://davidvongries.com/"
																	style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#2CB543;font-size:16px"><img
																			class="ssmtp-img ssmtp-signature"
																			src="http://swiftsmtp.com/wp-content/uploads/signature.jpg"
																			alt="David Vongries"
																			style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
																			width="90" title="David Vongries">
																</a>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table cellpadding="0" cellspacing="0" class="es-content" align="center"
						style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
					<tr>
						<td align="center" style="padding:0;Margin:0">
							<table bgcolor="#ffffff" class="es-content-body ssmtp-container" align="center"
									cellpadding="0"
									cellspacing="0"
									style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
								<tr>
									<td align="left" style="padding:0;Margin:0">
										<table cellpadding="0" cellspacing="0" width="100%"
												style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
											<tr>
												<td align="center" valign="top" class="ssmtp-container"
													style="padding:0;Margin:0;">
													<table cellpadding="0" cellspacing="0" width="100%"
															style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
														<tr>
															<td align="center" style="padding:0;Margin:0">
																<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#131313;font-size:14px;text-align:center">
																	David Vongries
																</p>
																<p
																		style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#131313;font-size:14px;text-align:center">
																	Founder, Swift SMTP
																</p>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
</body>

</html>
