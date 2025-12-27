<?php
$filename = 'todos.json';

if (!file_exists($filename)) {
    file_put_contents($filename, json_encode([]));
}

$todos = json_decode(file_get_contents($filename), true);

$editMode = false;
$editIndex = null;
$editTaskValue = '';

if (isset($_GET['edit'])) {
    $index = $_GET['edit'];
    if (isset($todos[$index])) {
        $editMode = true;
        $editIndex = $index;
        $editTaskValue = $todos[$index]['task'];
    }
}

if (isset($_POST['task'])) {
    $task = trim($_POST['task']);
    $postIndex = $_POST['edit_index'] ?? '';

    if (!empty($task)) {
        if ($postIndex !== '') {
            if (isset($todos[$postIndex])) {
                $todos[$postIndex]['task'] = $task;
            }
        } else {
            $todos[] = ['task' => $task, 'done' => false];
        }
        file_put_contents($filename, json_encode($todos));
    }
    header('Location: index.php');
    exit;
}

if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    if (isset($todos[$index])) {
        array_splice($todos, $index, 1);
        file_put_contents($filename, json_encode($todos));
    }
    header('Location: index.php');
    exit;
}

if (isset($_GET['toggle'])) {
    $index = $_GET['toggle'];
    if (isset($todos[$index])) {
        $todos[$index]['done'] = !$todos[$index]['done'];
        file_put_contents($filename, json_encode($todos));
    }
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS Docker Todo List V2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes gradient-xy {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 400% 400%;
            animation: gradient-xy 15s ease infinite;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
    </style>
</head>
<body class="bg-gradient-to-r from-pink-500 via-red-500 via-yellow-500 to-purple-500 animate-gradient min-h-screen flex items-center justify-center p-4">

    <div class="bg-white/95 backdrop-blur-md w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-white/50">
        <div class="bg-slate-900 p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-500 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-blue-500 rounded-full blur-3xl opacity-50 relative z-10"></div>
            
            <h1 class="text-3xl font-bold text-center relative z-10">
                <i class="fas fa-rocket mr-2 text-yellow-400"></i>what's up, Tasker!
            </h1>
            <p class="text-slate-400 text-center text-sm mt-2 relative z-10">Manage your project efficiently</p>
        </div>

        <div class="p-6 bg-slate-50">
            <form action="index.php" method="POST" class="relative">
                <input type="hidden" name="edit_index" value="<?= $editIndex !== null ? $editIndex : '' ?>">
                
                <div class="flex gap-2">
                    <input type="text" name="task" 
                           value="<?= htmlspecialchars($editTaskValue) ?>"
                           placeholder="Apa target hari ini?" 
                           class="w-full px-5 py-4 rounded-xl border-2 <?= $editMode ? 'border-orange-400 bg-orange-50' : 'border-slate-200 focus:border-purple-500' ?> focus:outline-none transition-all shadow-sm font-medium text-slate-700" 
                           required autocomplete="off" autofocus>
                    
                    <button type="submit" class="<?= $editMode ? 'bg-orange-500 hover:bg-orange-600' : 'bg-purple-600 hover:bg-purple-700' ?> text-white px-6 py-3 rounded-xl font-bold transition-all active:scale-95 shadow-lg shadow-purple-500/30 flex items-center justify-center min-w-[60px]">
                        <i class="fas <?= $editMode ? 'fa-save' : 'fa-plus' ?>"></i>
                    </button>
                </div>

                <?php if ($editMode): ?>
                    <div class="absolute -bottom-6 left-1 text-xs font-bold text-orange-500 flex items-center gap-1">
                        <span><i class="fas fa-pencil-alt"></i> Mengedit tugas...</span>
                        <a href="index.php" class="text-gray-400 hover:text-red-500 underline ml-2">Batal</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <ul class="px-6 pb-6 pt-2 space-y-3 max-h-[55vh] overflow-y-auto custom-scrollbar">
            <?php if (empty($todos)): ?>
                <div class="text-center py-12 text-slate-400 flex flex-col items-center animate-pulse">
                    <div class="bg-slate-100 p-4 rounded-full mb-3">
                        <i class="fas fa-mug-hot text-4xl text-slate-300"></i>
                    </div>
                    <p class="font-medium">Tugas kosong. Santai sejenak!</p>
                </div>
            <?php else: ?>
                <?php foreach (array_reverse($todos, true) as $index => $todo): ?>
                    <li class="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 group relative overflow-hidden">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1 <?= $todo['done'] ? 'bg-green-400' : 'bg-purple-500' ?>"></div>

                        <div class="flex items-center gap-4 overflow-hidden w-full pl-2">
                            <a href="?toggle=<?= $index ?>" class="text-2xl cursor-pointer transition-transform active:scale-90 <?= $todo['done'] ? 'text-green-500' : 'text-slate-200 hover:text-purple-500' ?>">
                                <i class="fas <?= $todo['done'] ? 'fa-check-circle' : 'fa-circle' ?>"></i>
                            </a>
                            
                            <span class="truncate font-medium text-lg w-full <?= $todo['done'] ? 'line-through text-slate-400 decoration-2 decoration-slate-300' : 'text-slate-700' ?>">
                                <?= htmlspecialchars($todo['task']) ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-1 ml-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                            <?php if (!$todo['done']): ?>
                                <a href="?edit=<?= $index ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-orange-400 hover:bg-orange-50 hover:text-orange-600 transition-colors" title="Edit Tugas">
                                    <i class="fas fa-pencil-alt text-sm"></i>
                                </a>
                            <?php endif; ?>

                            <a href="?delete=<?= $index ?>" onclick="return confirm('Yakin hapus?')" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-300 hover:bg-red-50 hover:text-red-500 transition-colors" title="Hapus Tugas">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        
        <div class="bg-slate-50 p-3 text-center border-t border-slate-100">
            <p class="text-[10px] text-slate-400 font-mono tracking-widest uppercase">Made By Rico Adi Pratama</p>
        </div>
    </div>

</body>
</html>