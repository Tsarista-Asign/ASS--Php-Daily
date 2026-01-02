// Gom tất cả JS từ các file gốc, generalize functions

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

function escapeAttr(s) {
    return String(s ?? '').replace(/['"]/g, m => m === '"' ? '&quot;' : '&#39;');
}

function openP(id) {
    document.getElementById(id).style.display = 'flex';
}

function closeP(id) {
    document.getElementById(id).style.display = 'none';
}

function openAdd(pageType) {
    openP('pAdd');
}

function openEdit(id, name, pageType, extraData = {}) {
    document.getElementById('eid').value = id;
    
    if (pageType === 'employees') {
        const tr = document.querySelector(`tr[data-id="${id}"]`);
        document.getElementById('elast').value = tr.querySelector('.c-last').textContent;
        document.getElementById('efirst').value = tr.querySelector('.c-first').textContent;
        document.getElementById('edep').value = tr.querySelector('.c-dep').dataset.dep || '';
        document.getElementById('erole').value = tr.querySelector('.c-role').dataset.role || '';
    } else if (pageType === 'users') {
        // For reset pass, etc.
    } else {
        document.getElementById('ename').value = name;
    }
    openP('pEdit');
}

async function reloadTable(endpoint, templateFunction) {
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'list');
    const r = await fetch(endpoint, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (!d.ok) return alert(d.error || 'Lỗi');
    const tb = document.querySelector('#tb tbody');
    tb.innerHTML = '';
    d.data.forEach(x => {
        const tr = document.createElement('tr');
        tr.dataset.id = x.id;
        tr.innerHTML = templateFunction(x); // Use page-specific template
        tb.appendChild(tr);
    });
}

async function delRow(id, endpoint) {
    if (!confirm('Xóa #' + id + '?')) return;
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'delete');
    fd.append('id', id);
    const r = await fetch(endpoint, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (d.ok) { reloadTable(endpoint, getTemplateForPage(document.body.dataset.page)); } else alert(d.error || 'Lỗi');
}

// Page-specific templates for table rows
function getTemplateForPage(pageType) {
    return function(x) {
        if (pageType === 'roles' || pageType === 'departments') {
            return `<td>${x.id}</td>
                <td class="c-name">${escapeHtml(x.name)}</td>
                <td>
                  ${isAdmin ? `<button class="btn" onclick="openEdit(${x.id}, '${escapeAttr(x.name)}', '${pageType}')">Sửa</button>
                  <button class="btn" onclick="delRow(${x.id}, '${getEndpointForPage(pageType)}')">Xóa</button>` : `<button class="btn" disabled title="Chỉ Admin">Sửa</button>
                  <button class="btn" disabled title="Chỉ Admin">Xóa</button>`}
                </td>`;
        } else if (pageType === 'employees') {
            return `<td>${x.id}</td>
                <td class="c-first">${escapeHtml(x.first)}</td>
                <td class="c-last">${escapeHtml(x.last)}</td>
                <td class="c-dep" data-dep="${x.department_id || ''}">${escapeHtml(x.department_name)}</td>
                <td class="c-role" data-role="${x.role_id || ''}">${escapeHtml(x.role_name)}</td>
                <td>
                  ${isAdmin ? `<button class="btn" onclick="openEdit(${x.id}, '', 'employees')">Sửa</button>
                  <button class="btn" onclick="delRow(${x.id}, 'listemployees.php')">Xóa</button>` : `<button class="btn" disabled title="Chỉ Admin">Sửa</button>
                  <button class="btn" disabled title="Chỉ Admin">Xóa</button>`}
                </td>`;
        } else if (pageType === 'users') {
            return `<td>${x.id}</td>
                <td>${escapeHtml(x.username)}</td>
                <td class="c-auth">${escapeHtml(x.auth)}</td>
                <td>${escapeHtml(x.created_at)}</td>
                <td>
                  <select onchange="setAuth(${x.id}, this.value)">
                    <option value="User" ${x.auth === 'User' ? 'selected' : ''}>User</option>
                    <option value="Admin" ${x.auth === 'Admin' ? 'selected' : ''}>Admin</option>
                  </select>
                  <button class="btn" onclick="openReset(${x.id})">Đặt lại mật khẩu</button>
                  <button class="btn" onclick="delUser(${x.id})">Xóa</button>
                </td>`;
        }
    };
}

// Get endpoint based on page
function getEndpointForPage(pageType) {
    const endpoints = {
        'roles': 'listroles.php',
        'departments': 'listdepartments.php',
        'employees': 'listemployees.php',
        'users': 'users.php'
    };
    return endpoints[pageType];
}

// Additional functions for users
async function setAuth(id, auth) {
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'set_auth');
    fd.append('id', id);
    fd.append('auth', auth);
    const r = await fetch('users.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (!d.ok) alert(d.error || 'Lỗi');
}

async function delUser(id) {
    if (!confirm('Xóa user #' + id + '?')) return;
    const fd = new FormData();
    fd.append('ajax', '1');
    fd.append('action', 'delete');
    fd.append('id', id);
    const r = await fetch('users.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const d = await r.json();
    if (d.ok) { reloadTable('users.php', getTemplateForPage('users')); } else alert(d.error || 'Lỗi');
}

function openReset(id) {
    const tr = document.querySelector(`tr[data-id="${id}"]`);
    document.getElementById('rid').value = id;
    document.getElementById('rname').value = tr.dataset.un || '';
    openP('pReset');
}

// Event listeners for forms (attached on load)
window.addEventListener('load', () => {
    const pageType = document.body.dataset.page;
    const endpoint = getEndpointForPage(pageType);
    const template = getTemplateForPage(pageType);

    // Add form
    document.getElementById('fAdd')?.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        fd.append('ajax', '1');
        fd.append('action', 'add');
        const r = await fetch(endpoint, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.ok) { closeP('pAdd'); reloadTable(endpoint, template); } else alert(d.error || 'Lỗi');
    });

    // Edit form
    document.getElementById('fEdit')?.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(e.target);
        fd.append('ajax', '1');
        fd.append('action', 'update');
        const r = await fetch(endpoint, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const d = await r.json();
        if (d.ok) { closeP('pEdit'); reloadTable(endpoint, template); } else alert(d.error || 'Lỗi');
    });

    // For users reset
    if (pageType === 'users') {
        document.getElementById('fReset')?.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(e.target);
            fd.append('ajax', '1');
            fd.append('action', 'reset_pass');
            const r = await fetch(endpoint, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const d = await r.json();
            if (d.ok) { closeP('pReset'); } else alert(d.error || 'Lỗi');
        });
    }

    // For login
    if (pageType === 'login') {
        document.getElementById('f').addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(e.target);
            fd.append('ajax', '1');
            const r = await fetch('login.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const d = await r.json();
            if (d.ok) { location.href = 'index.php'; } else { msg.textContent = d.error || 'Lỗi đăng nhập'; msg.style.display = 'block'; }
        });
    }

    // For index
    if (pageType === 'index') {
        document.getElementById('ensure')?.addEventListener('click', async () => {
            const fd = new FormData();
            fd.append('ajax', '1');
            fd.append('action', 'ensure');
            const r = await fetch('index.php', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const d = await r.json();
            m.className = 'msg ' + (d.ok ? 'ok' : 'err');
            m.textContent = d.ok ? (d.msg || 'OK') : (d.error || 'Lỗi');
            m.style.display = 'block';
        });
    }
});