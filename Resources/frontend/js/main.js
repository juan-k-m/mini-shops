;$(document).ready(function () {

  var body = $('body');
  if (body.hasClass('is--act-mitienda')) {
    var myShopNameInput  = $('#myShopNameInput');
    myShopNameInput.on('change',function(){
      checkIfSupplierNameExist(myShopNameInput.val());
    });
  }

  if (body.hasClass('is--act-productos')) {

    $('.product-name').on('click', function(){
    $(this).next().toggle(200);


    });

  }

});

function checkIfSupplierNameExist(supplierName){
  $.ajax({
    method:'POST',
    url: '/Vendedor/checkIfSupplierNameExist',
    data:{
      supplierName:supplierName
    },
    success:function(returnData){
      var alertDiv = $('.alert');
      alertDiv.hide();
      var targetDiv = $('#target_msg_div_error');
      console.log(true == returnData);
      console.log('true' == returnData);
      if ('true' == returnData) {
        alertDiv.show();
        targetDiv.html('Por favor seleccione otro nombre para la tienda ya que este ya fue tomado');
      }

    }


  });

}
