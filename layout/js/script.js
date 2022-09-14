// sidebar and nav
$(function(){
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
        swal('hello');
        alert('hello');
    });

    $(window).resize(function(e) {
      if($(window).width()<=768){
        $("#wrapper").removeClass("toggled");
      }else{
        $("#wrapper").addClass("toggled");
      }
    });
  });

//confirm delete
$('.confirm').click(function () {
    return confirm('Are you sure?');
});
//login page started js
$('.login-page h3 span').click(function () {
$(this).addClass('selected').siblings().removeClass('selected');
$('.login-page form').hide();
$('.'+$(this).data('click')).fadeIn(100);
})

// end login page

// $(function () {
//     'use strict';
    
// $('[placeholder]').focus(function () {
// $(this).attr('data-text',$(this).attr('placeholder'));
// // alert($(this).data('data'));
// $(this).attr('placeholder', '');
// }).blur(function () {
    
//      $(this).attr('placeholder', $(this).attr('data-text'));
// });
// });

// $('[placeholder]').addEventListener('focusin', (event) => {
//     alert('h')
//     $(this).attr('placeholder', '');
    
//   });
  
//   form.addEventListener('focusout', (event) => {
//     $(this).attr('data-text', $(this).attr('placeholder'));
//   });
//add astrix(*) for all required field
$('input').each(function () {
if($(this).attr('required')==='required'){
    $(this).after('<span class="astrix">*</span>');
}
});


//trigger select to select box it
// $('select').selectBoxIt(
//     {
//         autoWidth:false
//     }
// );
// $(document).ready(function() {
//     $('select').select2();
//     theme: "classic";
//     width: 'resolve'
// });

// new ads page
$('.live').keyup(function () {
    $($(this).data('class')).text($(this).val());
})
//sidebar toggle
//add class active if click on sidebar category
$(document).ready(function () {
    $('.catside a ').on('click', function () {

        $(this).parent().siblings('li ul').slideUp();


    });
    var t=$('a[aria-expanded=true]').parent('li');
    console.log(t);
});
//carousel product script

// $('#recipeCarousel').carousel({
//     interval: 4000
// })
$('.carousel .carousel-item:first-child').addClass('active');
$('.carousel .carousel-item').each(function(){
    var minPerSlide = 3;
    var next = $(this).next();
    if (!next.length) {
        next = $(this).siblings(':first');
    }
    next.children(':first-child').clone().appendTo($(this));

    for (var i=0;i<minPerSlide;i++) {
        next=next.next();
        if (!next.length) {
            next = $(this).siblings(':first');
        }

        next.children(':first-child').clone().appendTo($(this));
    }
});
var loadFile = function(event) {
    var image = document.getElementById('output');
    image.src = URL.createObjectURL(event.target.files[0]);
};

//End Carousel
//search box
//cart calculation
$(function () {
    // calculateSubTotal();
    $('#purchase').click(function () {
        var arrId=[];
        var arrQuantity=[];
        $('.productQ').each(function () {
            arrId.push($(this).data('id2'));
            arrQuantity.push($(this).val());
            // alert('this is id'+id);
            // alert('this is quantity'+q);
        })
        $.ajax({
            type:'POST',
            url:'updatecartitems.php',
            data:{
                cartItemId: arrId,
                cartItemQ:arrQuantity
            },
            success:function (data) {
                if (data=='success'){
                    location.href = "paymentfrm.php"
                    // swal.fire({
                    //     icon: 'success',
                    //     title: 'Purchase Has been Submitted',
                    //     showConfirmButton: false,
                    //     timer:3000});
                }
            }
        })

    })

    $('.remove').click(function () {
        var id=$(this).next().val();
        var these=$(this);
        $.ajax({
            type: 'POST',
            url: 'deletecartitem.php',
            data: {
                cartItemId:id
            },
            success:function (result) {
                if (result=='yes'){
                    these.parent().parent().fadeOut('slow',function () {
                        $(this).remove();
                        calculateTotal();
                        var count=parseInt($('.badge-c').text());
                        $('.badge-c').text(count-1);
                    });
                    // these.parent().parent().remove();
                }
            }

        })

    })
    $(".productQ").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $(this).next().html("Digits Only").show().fadeOut("slow");
            return false;
        }
    });
        var productQ = $('[data-id]');
    productQ.on('change keyup',function () {
                var these=($(this).data('id')).toString();
                var productQ = $('#'+these).val();
                var punitid=these.replace(/q/g,'p');
                var pUnitP = $('#'+punitid).val();
                var subTotal = productQ * pUnitP;
                var sub=these.replace(/q/g,'t');
                $('#'+sub).val(subTotal);
                calculateTotal()
        })
    calculateTotal();
    function  calculateTotal() {
        var AllSubtotal=0;
          $('.pSubTotal').each(function () {
              AllSubtotal +=parseFloat($(this).val());
             $('#total').val(AllSubtotal);
          })
    }

})
//send data from add-cart
$('#add-cart').on('click',function () {

    var productId=$('#product_id').text();
     // var pPrice=$('#price').text();
    $(this).attr('disabled',true);
    var count=parseInt($('.badge-c').text());
    $('.badge-c').text(count+1);
    $.ajax({
        type:'POST',
        url:'cart.php',
        data:{
            pId:productId
        },
        success:function (result) {
            swal.fire({
                icon: 'success',
                title: 'This item Added to your cart',
                showConfirmButton: false,
                timer:3000});
        }
        }
    )

})
//payment code
//set your publishable key
// Stripe.setPublishableKey('pk_test_TYooMQauvdEDq54NiTphI7jx');

// //callback to handle the response from stripe
// function stripeResponseHandler(status, response) {
//     if (response.error) {
//         //enable the submit button
//         swal("error in response");
//         alert("error in response")
//         $('#payBtn').removeAttr("disabled");
//         //display the errors on the form
//         $(".payment-errors").html(response.error.message);
//     } else {
//         var form$ = $("#paymentFrm");
//         //get token id
//         var token = response['id'];
//         //insert the token into the form
//         form$.append("<input type='hidden' name='stripeToken' value='" 
// + token + "' />");
//         //submit form to the server
//         form$.get(0).submit();
//     }
// }
// $(document).ready(function() {
//     //on form submit
//     $("#paymentFrm").submit(function(event) {
//         //disable the submit button to prevent repeated clicks
//         $('#payBtn').attr("disabled", "disabled");
        
//         //create single-use token to charge the user
//         Stripe.createToken({
//             number: $('.card-number').val(),
//             cvc: $('.card-cvc').val(),
//             exp_month: $('.card-expiry-month').val(),
//             exp_year: $('.card-expiry-year').val()
//         }, stripeResponseHandler);
        
//         //submit from callback
//         return false;
//     });
// });

// // click sign up and login if return from php
$('ul li a').click(function() {
    $('.lesson').removeClass('selected');
    $(this).closest('.lesson').addClass('selected');
});
// disable right click
$(document).bind("contextmenu",function(e){
    return false;
      });

// $('#signup-btn').click(function(e){
//     // e.preventDefault();
//     let pattern="^01[0-2]\d{1,8}$";
//     let tel=$("input[type=tel]").val();
//     if($('.pass').val()=="" && $('#name').val()=="" || $('#tel1').val()==""|| $('#tel2').val()==""){
//         swal.fire("من فضلك املا جميع البيانات اولا");
//     }
//     if(!pattern.match(tel)){
//         swal.fire("من فضلك ادخل رقم هاتف صحيح")
//     }
//     if($('#tel1').val()==$('#tel2').val()){
//         swal.fire("هاتفا الطالب وولى الامر لابد ان يكونا مختلفين")
//     }
//     if($('#class').val()=="" || $('#governorate').val()==""){
//         swal.fire("الصف الدراسي والمحافظة من فضلك اخترهما")
//     }
    
    
// })
function onlyNumberKey(evt) {
          
    // Only ASCII character in that range allowed
    var ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
}
$(document).ready(function(){$("button .ytp-copylink-icon .ytp-share-icon .ytp-chrome-top-buttons .ytp-chrome-top-buttons").hide()});

//rearrange row number
function renumberrows() {
    var rowsCount = $('#student tr');
    for (var n = 0; n < rowsCount.length; n++) {
        var firstCol = rowsCount[n].firstChild;
        firstCol.innerText = n + 1;
    }
}
$(window).scroll(function (event) {
    var scroll = $(window).scrollTop();
    // console.log(scroll);
    if(scroll>200){
        $('#bluebox').remove();
    }
    if(scroll>0 && scroll<286){
        $('#boxes').append('<div id="bluebox" style="width:100;height: 225px; background-color:transparent;position:fixed;top:600px;right:400px;z-index:1200"></div>');
    }
    if(scroll>540){
        $('#greenbox').remove();
    }
    if(scroll<540 && scroll >286){
        $('#boxes').append('<div id="greenbox" style="width:100;height: 225px; background-color:transparent;position:fixed;top:375px;right:400px;z-index:1200"></div>');
    }
    if(scroll>650){
        $('#greenbox').remove();
        $('#bluebox').remove();
        $('#redbox').remove();
    }else if(scroll<650 &&  scroll>540){
        $('#boxes').append('<div id="redbox" style="width:100;height: 225px; background-color:transparent;position:fixed;top:150px;right:400px;z-index:1200"></div>');

    }

});
// if (navigator.userAgent.includes('SamsungBrowser')) {
//     alert('Samsung browser is used')
//     console.log(navigator.userAgent);
//   }
