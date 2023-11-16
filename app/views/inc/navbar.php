<nav class="w-screen bg-teal-600 h-fit overflow-hidden">
    <div class="py-4 lg:px-8 px-4 max-w-[1280px] h-16 m-auto text-white flex items-center justify-between">
        <div>
            <h1 class="lg:text-2xl text-xl uppercase tracking-wider cursor-pointer font-bold">CRUD POO</h1>
        </div>
        <div class="flex lg:gap-8 gap-6 uppercase tracking-wider cursor-pointer text-lg items-center" id="navItems">
            <span class="group">
                <a href="<?php echo APP_URL; ?>dashboard/">Dashboard</a>
                <div class="w-0 group-hover:w-full h-0.5 bg-white ease-in-out duration-500"></div>
            </span>
            <span class="group">
                Usuarios
                <a href="<?php echo APP_URL; ?>userNew/">Nuevo</a>
                <a href="<?php echo APP_URL; ?>userList/">Lista</a>
                <div class="w-0 group-hover:w-full h-0.5 bg-white ease-in-out duration-500"></div>
            </span>
            <span class="group">
                <div class="w-0 group-hover:w-full h-0.5 bg-white ease-in-out duration-500"></div>
            </span>
        </div>
    </div>
</nav>