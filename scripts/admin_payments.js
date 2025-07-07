document.querySelectorAll('form[action="update_payment.php"]').forEach(f=>{
    f.addEventListener('submit',e=>{
      if(!confirm('Update payment status?')) e.preventDefault();
    });
  });  