window.fusionFormLogics={onReady:function(){jQuery(".fusion-form input, .fusion-form select, .fusion-form textarea").on("change keyup",function(){window.fusionFormLogics.formLogics(jQuery(this))})},formLogics:function(o=""){""===o?jQuery(".fusion-form.fusion-form-builder").each(function(){var o=jQuery(this).data("form-id");window.fusionFormLogics.applyLogics(o)}):window.fusionFormLogics.applyLogics(o.closest(".fusion-form-field").data("form-id"),o.attr("name"))},applyLogics:function(o,i=""){var n=jQuery(".fusion-form-form-wrapper.fusion-form-"+o).data("config").field_logics,e={};""!==i&&jQuery.each(n,function(o,n){-1===i.indexOf(o)&&-1===n.indexOf(i)||""===n||(e[o]=n)}),n=Object.keys(e).length?e:n,jQuery.each(n,function(i,n){window.fusionFormLogics.checkFieldLogic(i,n,o)})},checkFieldLogic:function(o,i,n){var e=""!==i?JSON.parse(i):[],r=jQuery(".fusion-form-"+n),t=r.find('[name="'+o+'"]'),s=!1,f=[];t=t.length?t:r.find('[name="'+o+'[]"]'),jQuery.each(e,function(o,i){var n,e=[],t=void 0!==i.operator?i.operator:"",s=void 0!==i.comparison?i.comparison:"",u=void 0!==i.field?i.field:"",a=void 0!==i.value?i.value:"";n=window.fusionFormLogics.getFieldValue(u,r),e.push(t),e.push("false"!==n&&window.fusionFormLogics.isMatch(n,a,s)),f.push(e)}),f.length&&(s=window.fusionFormLogics.MatchConditions(f),window.fusionFormLogics.toggleField(s,t))},toggleField:function(o,i){o?i.closest(".fusion-form-field").removeClass("fusion-form-field-hidden"):i.closest(".fusion-form-field").addClass("fusion-form-field-hidden")},getFieldValue:function(o,i){var n=i.find('[name="'+o+'"]'),e=-1!==jQuery.inArray(n.attr("type"),["checkbox","radio"]);return!n.closest(".fusion-form-field-hidden").hasClass("fusion-form-field-hidden")&&(e?window.fusionFormLogics.getArrayTypeValue(n,i):n.val())},getArrayTypeValue:function(o,i){var n=[];return"radio"===o.attr("type")?i.find('input[name="'+o.attr("name")+'"]:checked').val():(jQuery.each(i.find('input[name="'+o.attr("name")+'"]:checked'),function(){n.push(jQuery(this).val())}),n.join(" | "))},isMatch:function(o,i,n){switch(o=o?o.toLowerCase():"",i=i?i.toLowerCase():"",n){case"equal":return o===i;case"not-equal":return o!==i;case"greater-than":return parseFloat(o)>parseFloat(i);case"less-than":return parseFloat(o)<parseFloat(i);case"contains":return 0<=o.indexOf(i)}},MatchConditions:function(o){var i,n=null;if(-1==o.toString().indexOf("and")){for(i=0;i<o.length;i++)n=(n=null===n?o[i][1]:n)||o[i][1];return n}if(-1==o.toString().indexOf("or")){for(i=0;i<o.length;i++)n=(n=null===n?o[i][1]:n)&&o[i][1];return n}return window.fusionFormLogics.matchMixedConditions(o)},matchMixedConditions:function(o){var i,n=[],e="",r=0,t=0,s=o.length,f=[],u="",a="",c="",d="",l="";for(i=0;i<s;i++)void 0===n[r]&&(n[r]=[]),""===e||e==o[i][0]?(n[r][t]=o[i][1],t++,n[r][t]=o[i][0],t++,e=o[i][0]):(n[r][t]=o[i][1],t++,n[r][t]=o[i][0],r++,t=0,e="");if(jQuery.each(n,function(o,n){if(3>(s=n.length))return f.push(n[0]),void f.push(n[1]);for(i=0;i<s-1;i++)""===a?(d=n[i],l=n[i+2],c=n[i+1],a="or"===c?d||l:d&&l,i+=2):(d=a,l=n[i+1],c=n[i],a="or"===c?d||l:d&&l,i++),!0!==a&&(a=!1);u=n,f.push(a),f.push(u[s-1]),a=""}),a="",c="",d="",l="",3>(s=f.length))return f[0];for(i=0;i<s-1;i++)""===a?(d=f[i],l=f[i+2],c=f[i+1],i+=2,a="or"===c?d||l:d&&l):(d=a,l=f[i+1],c=f[i],a="or"===c?d||l:d&&l,i++),!0!==a&&(a=!1);return a}},function(o){o(document).ready(function(){window.fusionFormLogics.onReady(),window.fusionFormLogics.formLogics()})}(jQuery);