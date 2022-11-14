import { cookies } from './lib/cookies.js';

window.onload = function () {
    setInterval(function() {
        fetch("/getBalance").then(function(data) {
            document.getElementById("informerCurrencyUsd").textContent = data.usd
            document.getElementById("informerCurrencyUsdt").textContent = data.usdt
        });
    }, 5000)

    const allLanguages = document.querySelector('.all__languages'),
        selectedLanguages = document.querySelector('.selected__languages'),
        selectedLanguage = document.querySelector('.selected__language'),
        allLanguageLi = allLanguages.querySelectorAll('li'),
        selectedLanguageImg = selectedLanguage.querySelector('img'),
        selectedLanguageP = selectedLanguage.querySelector('p');


    allLanguageLi.forEach(el => el.addEventListener('click', () => {
        let src = el.querySelector('img').src;
    let language = el.querySelector('p').innerHTML;
    cookies.setItem('lng', el.dataset.languages, Infinity, '/', null, false);
    location.href = location.href;
    selectedLanguageImg.src = src;
    selectedLanguageP.innerHTML = language;

    selectedLanguage.dataset.languages = el.dataset.languages;
}))

    window.addEventListener('click', function (el) {
        if (el.target != selectedLanguages && selectedLanguage != el.target) {
            selectedLanguages.classList.remove('show');
        }
    })

    selectedLanguage.addEventListener('click', () => selectedLanguages.classList.toggle('show'));
};

document.addEventListener("DOMContentLoaded", _ => {
    // $(document).on("click", ".general_menu_icon_info_main_wrapper", function(){
    //     $(this).parent().toggleClass("active");
    // });
    const general_menu_icon_info_main_wrapper = document.querySelectorAll(".general_menu_icon_info_main_wrapper");
    if (general_menu_icon_info_main_wrapper) {
        general_menu_icon_info_main_wrapper.forEach(item => {
            item.addEventListener('click', event => {
                //const elem = event.target;
                //console.log(elem.parentNode.classList);
                item.parentNode.classList.toggle('active');
            });
        });
    }

    // $(document).on("click", ".realtime_gas_prices_nested_menu_wrapper", function(){
    //     $(this).parent().toggleClass("active");
    //     $(".money_info_nested_menu").removeClass("active");
    //     $(".questions_nested_menu ").removeClass("active");
    // });
    /** noop */

    // $(document).on("click", ".realtime_gas_prices_nested_menu_hidden_div_list_title_img_icon", function(){
    //     $(this).parent().toggleClass("active");
    // });
    /** noop */

    // $(document).on("click", ".money_info_nested_menu_title_icon", function(){
    //     $(this).parent().toggleClass("active");
    //     $(".realtime_gas_prices_nested_menu ").removeClass("active");
    //     $(".questions_nested_menu ").removeClass("active");
    // });
    /** noop */

    // $(document).on("click", ".questions_nested_menu_img_icon", function(){
    //     $(this).parent().toggleClass("active");
    //     $(".realtime_gas_prices_nested_menu ").removeClass("active");
    //     $(".money_info_nested_menu").removeClass("active");
    // });
    /** noop */

    // $(document).on("click", ".realtime_gas_prices_nested_menu_hidden_div_list_child_img_title_icon", function(){
    //     var val = $(this).data("value");
    //     var src = $(this).data("src");
    //     $(".realtime_gas_prices_nested_menu_hidden_div_list_img img").attr("src", src);
    //     $(".realtime_gas_prices_nested_menu_hidden_div_list_title").html(val);
    //     $(".realtime_gas_prices_nested_menu_hidden_div_list_child_img_title_icon").removeClass("active");
    //     $(this).addClass("active");
    //     $(".realtime_gas_prices_nested_menu_hidden_div_list").removeClass("active");
    // });
    /** noop */

    // $(document).on("click", ".money_info_nested_menu_hidden_div_item_btn", function(){
    //     var val = $(this).data("value");
    //     $(".money_info_nested_menu_title").html(val);
    //     $(".money_info_nested_menu_hidden_div_item_btn").removeClass("active");
    //     $(this).addClass("active");
    // })
    /** noop */

    // $(document).on("click", ".all_networks_hidden_div_btn", function(){
    //     var val = $(this).data("value");
    //     var src = $(this).data("src");
    //     $(".all_networks_title_img img").attr("src", src);
    //     $(".all_networks_title").html(val);
    //     $(".all_networks_hidden_div_btn").removeClass("open");
    //     $(this).addClass("open");
    //     $(this).parent().parent().find(".all_networks_title_img").addClass("open");
    //     $(".all_networks_wrapper").removeClass("open");
    // });
    /** noop */

    // $(document).on("click", ".assets_items_title_dop", function(){
    //     const data_id = $(this).data("id");

    //     const wasOpened = $(this).hasClass("active");

    //     $(".assets_item").removeClass("open");
    //     $(".assets_items_title").removeClass("active");
    //     // //$("#open_div1").addClass("open");
    //     $(".assets_item").first().addClass("open");
    //     $(".assets_items_title").first().addClass("active");

    //     if (wasOpened) {
    //         $(this).removeClass("active");
    //         $("#" + data_id).removeClass("open");
    //     } else {
    //         $(this).addClass("active");
    //         $("#" + data_id).addClass("open");
    //     }
    // })
    /** noop */

    // $(document).on("click", ".assets_items_title", function(){
    //     var data_id = $(this).data("id");

    //     $(".assets_items_title").removeClass("active");
    //     $(this).addClass("active");

    //     $(".assets_item").removeClass("open");
    //     $("#" + data_id).addClass("open");

    //     if (data_id == 'open_div1') {
    //         $("#open_div3").addClass("open");
    //         $(".assets_item_header-small").show();
    //     } else {
    //         $("#open_div3").removeClass("open");
    //         $(".assets_item_header-small").hide();
    //     }
    // })
    const assets_items_title = document.querySelectorAll(".assets_items_title");
    if (assets_items_title) {
        assets_items_title.forEach(item => {
            item.addEventListener('click', event => {
                const data_id = item.dataset.id;

                assets_items_title.forEach(bin => {
                    bin.classList.remove('active');
                });
                item.classList.add('active');

                document.querySelectorAll(".assets_item").forEach(bin => {
                    bin.classList.remove('open');
                });
                document.querySelector("#" + data_id).classList.add('open');

                if (data_id == 'open_div1') {
                    document.querySelector("#open_div3").classList.add('open');
                    document.querySelectorAll(".assets_item_header-small").forEach(bin => {
                        bin.style.display = '';
                    });
                } else {
                    document.querySelector("#open_div3").classList.remove('open');
                    document.querySelectorAll(".assets_item_header-small").forEach(bin => {
                        bin.style.display = 'none';
                    });
                }
            });
        });
    }

    // $(document).on("click", ".all_networks_title_icon_wrapper",function(){
    //     $(this).parent().toggleClass("open");
    // })
    /** noop */

    // $(document).on("input", ".theme_radio_input_field", function(){
    //     $(".theme_radio_input").removeClass("active");
    //     $(this).parent().addClass("active");
    // });
    /** not used */

    // $(document).on("click", ".language_title_icon_wrapper", function(){
    //     $(".language_popup").addClass("open");
    //     $("body").addClass("hidden_body");
    // })
    /** not used */

    // $(document).on("click", ".language_hidden_div_close_icon", function(){
    //     $(".language_popup").removeClass("open");
    //     $("body").removeClass("hidden_body");
    // })
    /** not used */

    // $(document).on("click", ".language_hidden_div_btn", function(){
    //     $(".language_hidden_div_btn").removeClass("open");
    //     $(this).addClass("open");
    //     $(".language_popup").removeClass("open");
    // })
    /** not used */

    // $(document).on("click", ".history_calculate_btn", function(){
    //     $(".calculate_taxes_popup").addClass("open");
    //     $("body").addClass("hidden_body");
    // })
    /** noop */

    // $(document).on("click", ".calculate_taxes_popup_close_icon", function(){
    //     $(".calculate_taxes_popup").removeClass("open");
    //     $("body").removeClass("hidden_body");
    // })
    /** not used */

    // $(document).on("click", ".history_main_item_child", function(){
    //     $(this).parent().find($(".history_main_item_child_hidden_parent")).slideToggle();
    //     $(this).parent().toggleClass("open");
    // })
    /** not used - in transactions histosy to select a row */

    // $(document).on("click", ".hamburger-menu-btn", function(){
    //     $(".mobile_version1").addClass("open");
    //     $("body").addClass("hidden_body");
    //     $(this).addClass("open");
    // });
    const hamburger_menu_btn = document.querySelector(".hamburger-menu-btn");
    if (hamburger_menu_btn) {
        hamburger_menu_btn.addEventListener('click', _ => {
            document.querySelector(".mobile_version1").classList.add('open');
            document.querySelector("body").classList.add('hidden_body');
            hamburger_menu_btn.classList.add('open');
        });
    }

    // $(document).on("click", ".mobile_version1_close", function(){
    //     $(".mobile_version1").removeClass("open");
    //     $("body").removeClass("hidden_body");
    //     $(".hamburger-menu-btn").removeClass("open");
    // });
    const mobile_version1_close = document.querySelector(".mobile_version1_close");
    if (mobile_version1_close) {
        mobile_version1_close.addEventListener('click', _ => {
            document.querySelector(".mobile_version1").classList.remove('open');
            document.querySelector("body").classList.remove('hidden_body');
            hamburger_menu_btn.classList.remove('open');
        });
    }

    // $(document).on("click", ".mobile_search_form_btn", function(){
    //     $(".mobile_version2").addClass("open");
    //     $("body").addClass("hidden_body");
    // });
    /** not used */

    // $(document).on("click", ".mobile_search_header_form_btn", function(){
    //     $(".mobile_version2").removeClass("open");
    //     $("body").removeClass("hidden_body");
    // });
    /** not used */

    // $(document).on("click", ".wallet_hidden_box_img", function(){
    //     $(".mobile_version3").addClass("open");
    //     $("body").addClass("hidden_body");
    // });
    /** noop */

    // $(document).on("click", ".mobile_version3_close", function(){
    //     $(".mobile_version3").removeClass("open");
    //     $("body").removeClass("hidden_body");
    // });
    /** not used */

    // $(document).on("click", ".invest_second_link", function(){
    //     $(this).parent().next('.invest_links_wrapper').find('.invest_link').show();
    //     return false;
    // });
    const invest_second_link = document.querySelectorAll(".invest_second_link");
    if (invest_second_link) {
        invest_second_link.forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                item.parentNode.nextElementSibling.querySelectorAll('.invest_link').forEach(bin => {
                    //console.log(bin);
                    bin.style.display = 'block';
                });
            });
        });
    }

    // $(document).on("click", "#open_div1 .assets_item_more_links", function(){
    //     $('#open_div1 .assets_item_link').show();
    //     $(this).hide();
    //     return false;
    // });
    const assets_item_more_links_1 = document.querySelectorAll("#open_div1 .assets_item_more_links");
    if (assets_item_more_links_1) {
        assets_item_more_links_1.forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                document.querySelectorAll("#open_div1 .assets_item_link").forEach(bin => {
                    bin.style.display = '';
                });
                item.style.display = 'none';
            });
        });
    }

    // $(document).on("click", "#open_div2 .assets_item_more_links", function(){
    //     $('#open_div2 .assets_item_link').show();
    //     $(this).hide();
    //     return false;
    // });
    const assets_item_more_links_2 = document.querySelectorAll("#open_div2 .assets_item_more_links");
    if (assets_item_more_links_2) {
        assets_item_more_links_2.forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                document.querySelectorAll("#open_div2 .assets_item_link").forEach(bin => {
                    bin.style.display = '';
                });
                item.style.display = 'none';
            });
        });
    }

    // $(document).on("click", ".more_transactions_btn", function(){
    //     $(this).parent().parent().find('.history_main_item').show();
    //     $(this).hide();
    //     return false;
    // });
    const more_transactions_btn = document.querySelector(".more_transactions_btn");
    if (more_transactions_btn) {
        more_transactions_btn.addEventListener('click', event => {
            event.preventDefault();
            more_transactions_btn.parentNode.parentNode.querySelectorAll(".history_main_item").forEach(bin => {
                bin.style.display = '';
            });
            more_transactions_btn.style.display = 'none';
        });
    }


    const header_lang_select = document.querySelector(".header_lang_select");
    if (header_lang_select) {
        const lng = cookies.getItem('lng');
        header_lang_select.value = lng ? lng : header_lang_select.value;
        header_lang_select.addEventListener('change', event => {
            cookies.setItem('lng', header_lang_select.value, Infinity, '/', null, false);
            location.href = location.href;
            //console.log(header_lang_select.value);
        });
    }

    const general_menu_langs_mob_a = document.querySelectorAll(".general_menu_langs_mob a");
    if (general_menu_langs_mob_a) {
        general_menu_langs_mob_a.forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                const lng = item.dataset.lng;
                cookies.setItem('lng', lng, Infinity, '/', null, false);
                location.href = location.href;
            });
        });
    }


    const windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

    if (windowWidth > 1023) {
        const home_user_buyandsell_box = document.querySelector(".home_user_buyandsell_box");
        if (home_user_buyandsell_box) {
            //home_user_buyandsell_box.style.width = Math.ceil(home_user_buyandsell_box.offsetWidth) + 200 + 'px';
            const right = (document.querySelector(".main_sections_wrapper").offsetWidth - document.querySelector(".tradingview-chart").offsetWidth
                            - home_user_buyandsell_box.offsetWidth)/2;
            home_user_buyandsell_box.style.right = Math.ceil(right) + 'px';
            //console.log(home_user_buyandsell_box.offsetWidth);
        }
    }
});

// window.addEventListener("load", _ => {
// });
