(()=>{let e;function t(e){document.querySelectorAll(".heatbox-tab-nav-item a").forEach(function(t){let n=t.parentElement;if(!n)return;let a=t.href;(a?a.split("#")[1]:"")===e?n.classList.add("active"):n.classList.remove("active")}),document.querySelectorAll("[data-show-when-tab]").forEach(function(t){let n=t.dataset.showWhenTab;(n?n.split(","):[]).map(e=>e.trim()).includes(e)?t.style.display="block":t.style.display="none"})}async function n(e){try{await navigator.clipboard.writeText(e)}catch(t){await a(e)}}async function a(e){let t=document.createElement("textarea");t.value=e,t.style.position="fixed",t.style.top="-3px",t.style.left="-3px",t.style.width="1px",t.style.height="1px",t.style.background="transparent",t.style.opacity="0",document.body.appendChild(t),t.focus(),t.select();try{document.execCommand("copy")}catch(e){console.error("Unable to copy text to clipboard:",e)}document.body.removeChild(t)}document.querySelectorAll(".heatbox-tab-nav-item a").forEach(function(e){e.addEventListener("click",function(n){let a=e.href;t(a?a.split("#")[1]:"")})}),window.addEventListener("load",function(e){let n=window.location.hash;t((n=n||"#smtp").replace("#",""))}),function(){(function(){let t=document.querySelector(".weed-tags-metabox");if(!t)return;let n=t.querySelectorAll("code");n.length&&n.forEach(t=>{t.addEventListener("click",e)})})();async function e(e){let t=e.target;if(!t)return;let a=t.innerText;if(!a)return;await n(a);let o=document.querySelector(".weed-tags-metabox .action-status");o&&(o.classList.add("is-shown"),setTimeout(()=>{o.classList.remove("is-shown")},1500))}}(),function(){let e=document.querySelectorAll("[data-show-when-checked]"),t=[],n=[],a=[];function o(e){let t=e.target.value,n=document.querySelector('[name="weed_settings[smtp_port]"]');n&&("ssl"===t?n.value="465":"tls"===t?n.value="587":n.value="25")}e.forEach(function(e){let o=e.dataset.showWhenChecked||"";if(!o)return;let c=document.querySelector('[name="'+o+'"]');c&&(n.includes(o)||(n.push(o),t.push(c)),a.push(e),c.checked?e.style.display="block":e.style.display="none")}),t.forEach(function(e){e.addEventListener("change",function(t){e.checked?a.forEach(function(t){let n=t.dataset.showWhenChecked||"";n&&n===e.name&&(t.style.display="block")}):a.forEach(function(t){let n=t.dataset.showWhenChecked||"";n&&n===e.name&&(t.style.display="none")})})}),document.querySelectorAll('[name="weed_settings[smtp_encryption]"]').forEach(function(e){e.addEventListener("change",o)})}(),e=!1,jQuery(document).on("click",".weed-reset-settings-button",function(e){confirm(weedSettings.warningMessages.resetSettings)||e.preventDefault()}),jQuery(document).on("click",".weed-test-email-button",function(t){t.preventDefault();let n=this;if(!n||e)return;e=!0,n&&n.classList.add("is-loading");let a={};switch(a.email_type=n.dataset.emailType,a.action="weed_test_emails",a.email_type){case"admin_new_user_notif_email":a.nonce=weedSettings.nonces.adminWelcomeEmail;break;case"user_welcome_email":a.nonce=weedSettings.nonces.userWelcomeEmail;break;case"reset_password_email":a.nonce=weedSettings.nonces.resetPasswordEmail}jQuery.ajax({url:ajaxurl,type:"post",dataType:"json",data:a}).always(function(){e=!1,n&&n.classList.remove("is-loading")})})})();
//# sourceMappingURL=settings.js.map
