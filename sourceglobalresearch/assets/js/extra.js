jQuery(document).ready(function ($) {

  if ($('.whitepaper_gravity_form').length > 0) {

    $('.page-intro__crumbs').hide();

    $('.arrow-link__text').each(function () {
      if ($(this).text() == "Download the white paper") {
        $(this).closest('a.arrow-link').addClass('trigger__whitepaper_gravity_form');
      }
    });
    $('.post_type_white-paper a.insight-content__documents-download-link').each(function () {
      $(this).addClass('trigger__whitepaper_gravity_form');
    });

    $('.arrow-link.subscribe-call-out__cta-link').addClass('trigger__whitepaper_gravity_form');

    $('a.trigger__whitepaper_gravity_form').each(function () {
      var u = $(this).attr('href');
      console.log(u);
      if (u != '#') {
        window.white_paper_url = u;
      }
      $(this).attr('href', '#');
    });



    $(document).on('click', '.trigger__whitepaper_gravity_form', function (e) {
      e.preventDefault();

      var formdata = $(".whitepaper_gravity_form form").first().serializeArray();
      console.log(formdata);

      var autosubmit_check_names = [
        'input_1.3',
        'input_1.6',
        'input_3',
        'input_5',
        'input_6'
      ];
      var autosubmit_check = 0;
      var manual_required = true;
      $.each(autosubmit_check_names, function (i, k) {
        console.log([k, autosubmit_check]);
        $.each(formdata, function (j, pair) {
          if (pair.name == k && pair.value != '') {
            autosubmit_check++;
          }
        });
      });
      if (autosubmit_check == autosubmit_check_names.length) {
        manual_required = false;
      }

      if ($(".whitepaper_gravity_form").hasClass("ui-dialog-content") &&
        $(".whitepaper_gravity_form").dialog("isOpen")) {
        $(".whitepaper_gravity_form").dialog("close");
      }



      $('.whitepaper_gravity_form').dialog({
        width: Math.min(1096, window.innerWidth * 0.75),
        title: "Download the paper",
        closeText: "close",
        modal: true,
        draggable: false,
        resizeable: false,
        maxHeight: Math.min(796, window.innerHeight * 0.75),
        classes: {
          "ui-dialog": "ui-corner-all"
        },
        open: function (event, ui) {
          console.log(manual_required);
          if (manual_required === false) {
            $(".whitepaper_gravity_form form").first().submit();
            $('.whitepaper_gravity_form  .sgravity-form__intro').text("Loading...");
            $('.whitepaper_gravity_form  .sgravity-form__form').hide();
          }
        }
      });


    });


    $(document).on('gform_confirmation_loaded', function (event, formId) {
      console.log(formId);
      if (formId == 4) {
        $('.whitepaper_gravity_form  .sgravity-form__form').show();
        $('.whitepaper_gravity_form  .sgravity-form__intro').hide();
        $('#gform_confirmation_wrapper_4').empty().text("Thank you for completing the form.").append($('<a></a>').attr('href', window.white_paper_url)
          .attr('target', '_blank').text("Please click here to download the file"));
        newWin = window.open(window.white_paper_url, '_blank');
        if (!newWin || newWin.closed || typeof newWin.closed == 'undefined') {
          //popup blocked
        } else {
          $(".whitepaper_gravity_form").dialog("close");
        }
        $('a.trigger__whitepaper_gravity_form').attr('href', window.white_paper_url).attr('target', '_blank').removeClass('trigger__whitepaper_gravity_form');
      }
    });


  }

if ($('#gform_wrapper_5').length > 0) {

    

    // $('.arrow-link__text').each(function () {
    //   if ($(this).text() == "Download an extract") {
    //     $(this).closest('a.arrow-link').addClass('trigger__report_extract_gravity_form');
    //   }
    // });
    // $('.post_type_white-paper a.insight-content__documents-download-link').each(function () {
    //   $(this).addClass('trigger__report_extract_gravity_form');
    // });

    $('.arrow-link.subscribe-call-out__cta-link').addClass('trigger__report_extract_gravity_form');

    $('a.trigger__report_extract_form').each(function () {
      var u = $(this).attr('href');
      console.log(u);
      // if (u != '#') {
      //   window.white_paper_url = u;
      // }
      $(this).attr('href', '#');
    });



    $(document).on('click', '.trigger__report_extract_gravity_form', function (e) {
      e.preventDefault();

      var formdata = $(".report_extract_form form").first().serializeArray();
      console.log(formdata);

      



      $('#gform_wrapper_5').dialog({
        width: Math.min(896, window.innerWidth * 0.75),
        title: "Download the report extracts",
        closeText: "close",
        modal: true,
        draggable: false,
        resizeable: false,
        maxHeight: Math.max(800, window.innerHeight * 0.75),
        classes: {
          "ui-dialog": "ui-corner-all"
        },
        open: function (event, ui) {
         
        }
      });


    });

    $("#gform_submit_button_5").on('click', function(){
      console.log("submit clicked report extracts");
      
    });

  }
  

  if ($('#gform_wrapper_7').length > 0) {
    
    $(document).on('click', '.report-notf-link a.anon-user', function (e) {
      e.preventDefault();

      var formdata = $(".report_notification_form form").first().serializeArray();
      console.log(formdata);

      

      console.log("report notification loaded");

      $('#gform_wrapper_7').dialog({
        width: Math.min(896, window.innerWidth * 0.75),
        title: "Sign up for report notification",
        closeText: "close",
        modal: true,
        draggable: false,
        resizeable: false,
        maxHeight: Math.max(590, window.innerHeight * 0.75),
        classes: {
          "ui-dialog": "ui-corner-all"
        },
        open: function (event, ui) {
         
         
        }
      });
    });

    $(document).on('click', '.report-notf-link a.auth-user', function (e) {
      var um = null; var fn = null;
      e.preventDefault();
      var base_url = window.location.origin;
      console.log("auth users report notf");
      $.ajax({
            url: base_url + "/wp-admin/admin-ajax.php?action=swp_serveMe",
            method: "GET",    
            async: false,      
            success: function (j) {
             
              if (j.success && typeof j.data != 'undefined') {
                console.log(j.data.user);
                    if (typeof j.data.user != 'undefined') {
                      um = j.data.user.mail;
                      fn = j.data.user.fullname;
                    }
              }
            }
        });
      
      path = $(location).attr("pathname"); 
      //pathsub = path.substr(path.indexOf("reports/") + 8);
      pathsub = path.split("reports/").pop();
      nid = pathsub.split("-").shift();
      console.log(nid);
      console.log({report_nid : nid, user_info : {email : um,name: fn}});
        $.ajax({
          url: "https://reports.sourceglobalresearch.com/notifyusersonpublish",
          method: "POST",
          data: {report_nid : nid, user_info : {email : um,name: fn}},
          success: function (result) {
            $('.report-notf-success').show();
            $('.report-notf-link').hide();
          }
      });
    });
      
  }

});
