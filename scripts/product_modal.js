document.addEventListener("DOMContentLoaded",()=>{
    const modal=document.getElementById('addModal');
    const btn  =document.getElementById('showAddModal');
    const span =modal.querySelector('.close');
    const form =document.getElementById('addProductForm');
    const result= document.getElementById('addResult');
  
    btn.onclick = ()=>{ modal.style.display='block'; };
    span.onclick= ()=>{ modal.style.display='none'; };
    window.onclick=e=>{ if(e.target===modal) modal.style.display='none'; };
  
    form.addEventListener('submit',async e=>{
      e.preventDefault();
      result.textContent='Saving...';
      const res=await fetch('add_product.php',{method:'POST',body:new FormData(form)});
      const txt=await res.text();
      if(txt.trim()==='success'){
        result.textContent='✅ Saved! Refreshing...';
        setTimeout(()=>location.reload(),800);
      }else{
        result.textContent='❌ '+txt;
      }
    });
  });  