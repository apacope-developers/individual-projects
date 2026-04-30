<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel — LifeLine</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:#09090B;color:#fafafa;min-height:100vh}
h1,h2,h3,h4,h5,h6{font-family:'Space Grotesk',sans-serif}
.bg-grid{background-image:radial-gradient(ellipse 80% 50% at 50% 0%,rgba(239,68,68,.06) 0%,transparent 60%),radial-gradient(ellipse 60% 40% at 80% 100%,rgba(45,212,191,.04) 0%,transparent 60%),linear-gradient(rgba(63,63,70,.15) 1px,transparent 1px),linear-gradient(90deg,rgba(63,63,70,.15) 1px,transparent 1px);background-size:100% 100%,100% 100%,40px 40px,40px 40px}
.table-row{border-bottom:1px solid #27272A;transition:background .15s}
.table-row:hover{background:rgba(255,255,255,.03)}
.stat-card{background:#18181B;border:1px solid #27272A;border-radius:12px;padding:20px;transition:all .25s}
.stat-card:hover{border-color:#3F3F46;transform:translateY(-2px)}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;transition:all .2s}
.btn-danger{background:#DC2626;color:white;border:none}.btn-danger:hover{background:#B91C1C}
.btn-ghost{background:transparent;color:#A1A1AA;border:1px solid #2722A}.btn-ghost:hover{background:#18181B;border-color:#3F3F46;color:#FAFAFA}
.badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:6px;font-size:11px;font-weight:600}
.badge-red{background:rgba(239,68,68,.15);color:#FCA5A5}
.badge-teal{background:rgba(45,212,191,.15);color:#2DD4BF}
.sidebar-link{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;font-size:13px;color:#A1A1AA;transition:all .15s;cursor:pointer;text-decoration:none}
.sidebar-link:hover{background:rgba(255,255,255,.05);color:#FAFAFA}
.sidebar-link.active{background:rgba(239,68,68,.1);color:#EF4444}
.toast{position:fixed;bottom:24px;right:24px;z-index:9999;padding:12px 20px;border-radius:10px;background:#27272A;border:1px solid #3F3F46;font-size:13px;animation:toastIn .3s ease,toastOut .3s ease 2.7s forwards}
@keyframes toastIn{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes toastOut{from{opacity:1}to{opacity:0;transform:translateY(20px)}}
@media(max-width:768px){.admin-sidebar{display:none}.admin-main{margin-left:0}}
</style>
</head>
<body class="bg-grid">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <aside class="admin-sidebar w-56 bg-black/50 border-r border-zinc-800/50 flex flex-col py-5 flex-shrink-0 fixed h-full z-10">
    <div class="px-4 mb-6 flex items-center gap-3">
      <div class="w-9 h-9 rounded-lg bg-red-600 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-heart-pulse text-white text-sm"></i></div>
      <div><h1 class="font-bold text-sm leading-tight">LifeLine</h1><p class="text-[10px] text-zinc-500">Admin Panel</p></div>
    </div>
    <nav class="flex-1 flex flex-col gap-1 px-2">
      <a href="/admin" class="sidebar-link active"><i class="fa-solid fa-gauge-high w-5 text-center"></i><span>Dashboard</span></a>
      <a href="/admin" class="sidebar-link"><i class="fa-solid fa-users w-5 text-center"></i><span>All Users</span></a>
      
      <!-- Content Management -->
      <div class="px-2 py-2">
        <p class="text-xs text-zinc-500 font-medium mb-2">CONTENT MANAGEMENT</p>
      </div>
      <a href="{{ route('admin.body-map') }}" class="sidebar-link"><i class="fa-solid fa-person w-5 text-center"></i><span>Body Map</span></a>
      <a href="{{ route('admin.symptom-checker') }}" class="sidebar-link"><i class="fa-solid fa-stethoscope w-5 text-center"></i><span>Symptom Checker</span></a>
      <a href="{{ route('admin.first-aid-guide') }}" class="sidebar-link"><i class="fa-solid fa-book-medical w-5 text-center"></i><span>First Aid Guide</span></a>
      <a href="{{ route('admin.kit-inventory') }}" class="sidebar-link"><i class="fa-solid fa-kit-medical w-5 text-center"></i><span>Kit Inventory</span></a>
      <a href="{{ route('admin.contacts') }}" class="sidebar-link"><i class="fa-solid fa-phone-volume w-5 text-center"></i><span>Emergency Contacts</span></a>
      
      <div class="px-2 py-2 mt-2">
        <p class="text-xs text-zinc-500 font-medium mb-2">SYSTEM</p>
      </div>
      <a href="/dashboard" class="sidebar-link"><i class="fa-solid fa-house-medical w-5 text-center"></i><span>View Site</span></a>
    </nav>
    <div class="px-4 mt-auto pt-4 border-t border-zinc-800/50">
      <div class="flex items-center gap-3 px-2">
        <div class="w-8 h-8 rounded-full bg-red-600/20 flex items-center justify-center"><i class="fa-solid fa-user-shield text-red-400 text-xs"></i></div>
        <div><p class="text-xs font-medium">{{ Auth::user()->name }}</p>
        @if(Auth::user()->is_admin)
        <p class="text-[10px] text-zinc-500">Administrator</p>
        @else
        <p class="text-[10px] text-zinc-500">User</p>
        @endif
        </div>
      </div>
      <form method="POST" action="/logout" class="mt-3 px-2">
        @csrf
        <button type="submit" class="sidebar-link w-full text-red-400 hover:!text-red-300"><i class="fa-solid fa-right-from-bracket w-5 text-center"></i><span>Logout</span></button>
      </form>
    </div>
  </aside>

  <!-- Main -->
  <main class="flex-1 admin-main ml-56 p-6 md:p-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
      <div>
        <h2 class="text-2xl font-bold">Admin Dashboard</h2>
        <p class="text-zinc-500 text-sm">Manage users and monitor system activity</p>
      </div>
      @if(session('success'))
        <div class="bg-green-600/10 border border-green-600/20 rounded-lg px-4 py-2 text-sm text-green-400">
          <i class="fa-solid fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
      @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
          <p class="text-zinc-500 text-xs">Total Users</p>
          <div class="w-8 h-8 rounded-lg bg-teal-600/20 flex items-center justify-center"><i class="fa-solid fa-users text-teal-400 text-xs"></i></div>
        </div>
        <p class="text-3xl font-bold" style="color:#2DD4BF">{{ $userCount }}</p>
      </div>
      <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
          <p class="text-zinc-500 text-xs">Admin Accounts</p>
          <div class="w-8 h-8 rounded-lg bg-red-600/20 flex items-center justify-center"><i class="fa-solid fa-user-shield text-red-400 text-xs"></i></div>
        </div>
        <p class="text-3xl font-bold" style="color:#EF4444">{{ $adminCount }}</p>
      </div>
      <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
          <p class="text-zinc-500 text-xs">System Status</p>
          <div class="w-8 h-8 rounded-lg bg-green-600/20 flex items-center justify-center"><i class="fa-solid fa-circle-check text-green-400 text-xs"></i></div>
        </div>
        <p class="text-lg font-bold text-green-400">Online</p>
      </div>
      <div class="stat-card">
        <div class="flex items-center justify-between mb-2">
          <p class="text-zinc-500 text-xs">Database</p>
          <div class="w-8 h-8 rounded-lg bg-blue-600/20 flex items-center justify-center"><i class="fa-solid fa-database text-blue-400 text-xs"></i></div>
        </div>
        <p class="text-lg font-bold text-blue-400">MySQL</p>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-[#18181B] border border-zinc-800 rounded-xl overflow-hidden">
      <div class="p-4 border-b border-zinc-800 flex items-center justify-between">
        <h3 class="font-semibold text-sm"><i class="fa-solid fa-users mr-2 text-zinc-400"></i>Registered Users</h3>
        <span class="badge badge-teal">{{ $users->count() }} total</span>
      </div>

      @if($users->isEmpty())
        <div class="p-12 text-center">
          <i class="fa-solid fa-users-slash text-3xl text-zinc-700 mb-3"></i>
          <p class="text-zinc-500">No registered users yet</p>
        </div>
      @else
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-zinc-800 text-left">
                <th class="px-4 py-3 text-xs text-zinc-400 font-medium">User</th>
                <th class="px-4 py-3 text-xs text-zinc-400 font-medium">Email</th>
                <th class="px-4 py-3 text-xs text-zinc-400 font-medium">Joined</th>
                <th class="px-4 py-3 text-xs text-zinc-400 font-medium text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr class="table-row">
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center flex-shrink-0"><i class="fa-solid fa-user text-zinc-500 text-xs"></i></div>
                    <div>
                      <p class="text-sm font-medium">{{ $user->name }}</p>
                      <p class="text-[11px] text-zinc-600">ID: {{ $user->id }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-3 text-sm text-zinc-400">{{ $user->email }}</td>
                <td class="px-4 py-3 text-xs text-zinc-500">{{ $user->created_at->format('M j, Y') }}</td>
                <td class="px-4 py-3 text-right">
                  <form method="POST" action="/admin/users/{{ $user->id }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
                    @csrf
                    <button type="submit" class="btn btn-danger text-xs px-3 py-1.5">
                      <i class="fa-solid fa-trash-can"></i> Delete
                    </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    <p class="text-zinc-600 text-xs mt-6 text-center">&copy; 2025 LifeLine Emergency First Aid System — Admin Panel</p>
  </main>
</div>

<div id="toastArea"></div>

<script>
// Move toast that Laravel session messages auto-dismiss
setTimeout(function(){
  var t = document.querySelector('.bg-green-600\\/10');
  if(t) setTimeout(function(){ t.remove() }, 4000);
}, 500);
</script>
</body>
</html>