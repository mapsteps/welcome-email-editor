(()=>{function e(e){document.querySelectorAll(".heatbox-tab-nav-item a").forEach(function(t){let n=t.parentElement;if(!n)return;let s=t.href;(s?s.split("#")[1]:"")===e?n.classList.add("active"):n.classList.remove("active")}),document.querySelectorAll("[data-show-when-tab]").forEach(function(t){let n=t.dataset.showWhenTab;(n?n.split(","):[]).map(e=>e.trim()).includes(e)?t.style.display="block":t.style.display="none"})}async function t(e){try{await navigator.clipboard.writeText(e)}catch(t){await n(e)}}async function n(e){let t=document.createElement("textarea");t.value=e,t.style.position="fixed",t.style.top="-3px",t.style.left="-3px",t.style.width="1px",t.style.height="1px",t.style.background="transparent",t.style.opacity="0",document.body.appendChild(t),t.focus(),t.select();try{document.execCommand("copy")}catch(e){console.error("Unable to copy text to clipboard:",e)}document.body.removeChild(t)}document.querySelectorAll(".heatbox-tab-nav-item a").forEach(function(t){t.addEventListener("click",function(n){let s=t.href;e(s?s.split("#")[1]:"")})}),window.addEventListener("load",function(t){let n=window.location.hash;e((n=n||"#smtp").replace("#",""))}),function(){(function(){let t=document.querySelector(".weed-tags-metabox");if(!t)return;let n=t.querySelectorAll("code");n.length&&n.forEach(t=>{t.addEventListener("click",e)})})();async function e(e){let n=e.target;if(!n)return;let s=n.innerText;if(!s)return;await t(s);let a=document.querySelector(".weed-tags-metabox .action-status");a&&(a.classList.add("is-shown"),setTimeout(()=>{a.classList.remove("is-shown")},1500))}}(),function(){let e=document.querySelectorAll("[data-show-when-checked]"),t=[],n=[],s=[];function a(e){let t=e.target.value,n=document.querySelector('[name="weed_settings[smtp_port]"]');n&&("ssl"===t?n.value="465":"tls"===t?n.value="587":n.value="25")}e.forEach(function(e){let a=e.dataset.showWhenChecked||"";if(!a)return;let c=document.querySelector('[name="'+a+'"]');c&&(n.includes(a)||(n.push(a),t.push(c)),s.push(e),c.checked?e.style.display="block":e.style.display="none")}),t.forEach(function(e){e.addEventListener("change",function(t){e.checked?s.forEach(function(t){let n=t.dataset.showWhenChecked||"";n&&n===e.name&&(t.style.display="block")}):s.forEach(function(t){let n=t.dataset.showWhenChecked||"";n&&n===e.name&&(t.style.display="none")})})}),document.querySelectorAll('[name="weed_settings[smtp_encryption]"]').forEach(function(e){e.addEventListener("change",a)})}(),function(){let e=!1,t=document.querySelector(".weed-submission-notice");jQuery(document).on("click",".weed-test-email-button",function(n){n.preventDefault();let s=this;if(!s||e)return;e=!0,s&&s.classList.add("is-loading");let a={};switch(a.email_type=s.dataset.emailType,a.action="weed_test_emails",a.email_type){case"admin_new_user_notif_email":a.nonce=weedSettings.nonces.adminWelcomeEmail;break;case"user_welcome_email":a.nonce=weedSettings.nonces.userWelcomeEmail;break;case"reset_password_email":a.nonce=weedSettings.nonces.resetPasswordEmail;break;case"test_smtp_email":a.nonce=weedSettings.nonces.testSmtpEmail;let c=document.querySelector("#weed_settings--test_smtp_recipient_email");a.to_email=c?c.value:""}t&&(t.classList.add("is-hidden"),t.classList.remove("is-error"),t.classList.remove("is-success")),jQuery.ajax({url:ajaxurl,type:"post",dataType:"json",data:a}).always(function(n){e=!1,s&&s.classList.remove("is-loading"),console.log(n),t&&(t.classList.remove("is-hidden"),t.classList.add(n.success?"is-success":"is-error"),t.innerHTML=n.data?n.data:"")})})}(),jQuery(document).on("click",".weed-reset-settings-button",function(e){confirm(weedSettings.warningMessages.resetSettings)||e.preventDefault()})})();
//# sourceMappingURL=settings.js.map
