import jQuery from "jquery";

export function setupTabs($: typeof jQuery): void {
	$(".heatbox-tab-nav-item").on("click", function (_e) {
		$(".heatbox-tab-nav-item").removeClass("active");
		this.classList.add("active");
	});

	$(".smtp-panel").on("click", function (_e) {
		$(".heatbox-admin-panel").css("display", "none");
		$(".weed-smtp-panel").css("display", "block");
	});

	$(".welcome-email-panel").on("click", function (_e) {
		$(".heatbox-admin-panel").css("display", "none");
		$(".weed-welcome-email-panel").css("display", "block");
	});

	$(".misc-panel").on("click", function (_e) {
		$(".heatbox-admin-panel").css("display", "none");
		$(".weed-misc-panel").css("display", "block");
	});

	window.addEventListener("load", function (_e) {
		let hash = window.location.hash;

		if (!hash) {
			hash = "#smtp";
		}

		if ("#smtp" === hash) {
			$(".heatbox-tab-nav-item.smtp-panel").addClass("active");
			$(".weed-smtp-panel").css("display", "block");
		}

		if ("#welcome-email" === hash) {
			$(".heatbox-tab-nav-item.welcome-email-panel").addClass("active");
			$(".weed-welcome-email-panel").css("display", "block");
		}

		if ("#misc" === hash) {
			$(".heatbox-tab-nav-item.misc-panel").addClass("active");
			$(".weed-misc-panel").css("display", "block");
		}
	});
}