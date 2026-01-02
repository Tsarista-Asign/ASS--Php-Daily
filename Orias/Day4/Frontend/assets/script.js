// Script cho dự án MySQL demo

function showMsg(ok, msg) {
    const m = document.getElementById('msg');
    m.className = 'msg ' + (ok ? 'ok' : 'err');
    m.textContent = msg;
    m.style.display = 'block';
    setTimeout(() => m.style.display = 'none', 3000);
}

async function runAction(action, confirmMsg = null) {
    if (confirmMsg && !confirm(confirmMsg)) return;
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', action);
    const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (d.ok) {
        reloadTable();
        showMsg(true, d.msg || 'Thành công');
    } else {
        showMsg(false, d.error || 'Lỗi');
    }
}

async function reloadTable() {
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'list');
    const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    const tb = document.getElementById('tb');
    const noTable = document.getElementById('no-table');
    if (d.exists) {
        noTable.style.display = 'none';
        tb.style.display = 'table';
        const tbody = tb.querySelector('tbody');
        tbody.innerHTML = '';
        d.data.forEach(x => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${x.id}</td>
                <td>${escapeHtml(x.firstname)}</td>
                <td>${escapeHtml(x.lastname)}</td>
                <td>${escapeHtml(x.reg_date)}</td>`;
            tbody.appendChild(tr);
        });
    } else {
        tb.style.display = 'none';
        noTable.style.display = 'block';
    }
}

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

window.addEventListener('load', reloadTable);