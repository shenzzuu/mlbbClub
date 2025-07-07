let selectedCard = null;

// ------------ Edit flow ------------
function editStaff(btn){
  selectedCard = btn.closest('.staff-card');
  document.getElementById('editName').value  = selectedCard.querySelector('h4').innerText;
  document.getElementById('editRole').value  = selectedCard.querySelectorAll('p')[0].innerText;
  document.getElementById('editEmail').value = selectedCard.querySelectorAll('p')[1].innerText;
  document.getElementById('editModal').style.display='flex';
}
function saveEdit(){
  if(!selectedCard) return;
  selectedCard.querySelector('h4').innerText          = document.getElementById('editName').value;
  selectedCard.querySelectorAll('p')[0].innerText     = document.getElementById('editRole').value;
  selectedCard.querySelectorAll('p')[1].innerText     = document.getElementById('editEmail').value;
  document.getElementById('editModal').style.display='none';
}

// ------------ Delete ------------
function deleteStaff(btn){
  const card = btn.closest('.staff-card');
  if(confirm('Delete this staff?')) card.remove();
}

// ------------ Add flow ------------
function showAddModal(){ document.getElementById('addModal').style.display='flex'; }
function addStaff(){
  const name  = document.getElementById('newName').value.trim();
  const role  = document.getElementById('newRole').value.trim();
  const email = document.getElementById('newEmail').value.trim();
  const img   = document.getElementById('newImg').value.trim() || 'pictures/default.jpg';

  if(!name || !role || !email){ alert('Fill in all fields'); return; }

  const card = document.createElement('div');
  card.className = 'staff-card';
  card.innerHTML = `
    <img src="${img}" alt="${name}">
    <h4>${name}</h4><p>${role}</p><p>${email}</p>
    <div class="staff-actions">
      <button class="edit-btn" onclick="editStaff(this)">Edit</button>
      <button onclick="deleteStaff(this)">Delete</button>
    </div>
  `;
  document.getElementById('staffContainer').appendChild(card);
  document.getElementById('addModal').style.display='none';
  ['newName','newRole','newEmail','newImg'].forEach(id=>document.getElementById(id).value='');
}

// ------------ Close modals on outside click ------------
window.onclick = e=>{
  if(e.target.id==='editModal') document.getElementById('editModal').style.display='none';
  if(e.target.id==='addModal')  document.getElementById('addModal').style.display='none';
};