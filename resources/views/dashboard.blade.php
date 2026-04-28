<x-layouts.app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-zinc-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>

                    <flux:card>
                        <div class="p-4">
                            <p>Selamat datang di dashboard! Anda telah berhasil login.</p>
                            <p class="mt-2">Ini adalah contoh penggunaan Flux UI component dalam dashboard.</p>
                        </div>
                    </flux:card>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <flux:card>
                            <h3 class="font-medium mb-2">Card 1</h3>
                            <p>Konten card 1</p>
                        </flux:card>

                        <flux:card>
                            <h3 class="font-medium mb-2">Card 2</h3>
                            <p>Konten card 2</p>
                        </flux:card>

                        <flux:card>
                            <h3 class="font-medium mb-2">Card 3</h3>
                            <p>Konten card 3</p>
                        </flux:card>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
