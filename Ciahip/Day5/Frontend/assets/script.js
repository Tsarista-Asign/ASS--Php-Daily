// Script dành riêng cho dự án Students

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

function openP(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeP(id) {
    document.getElementById(id).style.display = 'none';
}

function openAdd() {
    openP('pAdd');
}

function openEdit(id) {
    document.getElementById('eid').value = id;
    const tr = document.querySelector(`tr[data-id="${id}"]`);
    document.getElementById('ehoten').value = tr.querySelector('.c-hoten').textContent;
    document.getElementById('egioitinh').value = tr.querySelector('.c-gioitinh').textContent;
    document.getElementById('engaysinh').value = tr.querySelector('.c-ngaysinh').textContent;
    openP('pEdit');
}

async function reloadTable() {
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'list');
    const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (!d.ok) return alert(d.error || 'Lỗi');
    const tb = document.querySelector('#tb tbody');
    tb.innerHTML = '';
    d.data.forEach(x => {
        const tr = document.createElement('tr');
        tr.dataset.id = x.id;
        tr.innerHTML = `<td>${x.id}</td>
            <td class="c-hoten">${escapeHtml(x.hoten)}</td>
            <td class="c-gioitinh">${escapeHtml(x.gioitinh)}</td>
            <td class="c-ngaysinh">${escapeHtml(x.ngaysinh)}</td>
            <td>
                <button class="btn" onclick="openEdit(${x.id})">Sửa</button>
                <button class="btn" onclick="delRow(${x.id})">Xóa</button>
            </td>`;
        tb.appendChild(tr);
    });
}

async function delRow(id) {
    if (!confirm('Xóa #' + id + '?')) return;
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'delete');
    fd.append('id', id);
    const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (d.ok) { reloadTable(); } else alert(d.error || 'Lỗi');
}

// Event listeners
window.addEventListener('load', () => {
    document.getElementById('fAdd')?.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        fd.append('ajax', '1');
        fd.append('action', 'add');
        const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.ok) { closeP('pAdd'); reloadTable(); } else alert(d.error || 'Lỗi');
    });

    document.getElementById('fEdit')?.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        fd.append('ajax', '1');
        fd.append('action', 'update');
        const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.ok) { closeP('pEdit'); reloadTable(); } else alert(d.error || 'Lỗi');
    });
});