<div class="flex items-center justify-center min-h-screen bg-white font-[Montserrat] p-4">
    <div class="w-full max-w-[840px] border-0 md:border-[3px] md:border-black flex flex-col md:flex-row">

        <!-- Form Side (Order 2 on Mobile, Order 1 on Desktop) -->
        <div class="flex flex-col items-center justify-center w-full md:w-1/2 p-6 gap-2 md:gap-4 order-2 md:order-1">
            <a href="{{ route('landing') }}">
                <img src="{{ asset('assets/images/fluffy-blue.png') }}" alt="Fluffy Logo" class="h-20 mb-4">
            </a>
            
            <h3 class="font-medium text-[20px] mb-4 text-center">Create Employee Account</h3>

            @if (session()->has('message'))
                <div class="w-full text-center text-green-600 mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="create" class="flex flex-col gap-4 w-full max-w-[330px]">
                <div>
                    <input type="text" wire:model="name" placeholder="Name" class="w-full h-[40px] border-[1.5px] border-black px-3 rounded-none focus:ring-0 focus:border-black" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <input type="email" wire:model="email" placeholder="Email" class="w-full h-[40px] border-[1.5px] border-black px-3 rounded-none focus:ring-0 focus:border-black" required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <input type="password" wire:model="password" placeholder="Password" class="w-full h-[40px] border-[1.5px] border-black px-3 rounded-none focus:ring-0 focus:border-black" required>
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <input type="password" wire:model="password_confirmation" placeholder="Confirm Password" class="w-full h-[40px] border-[1.5px] border-black px-3 rounded-none focus:ring-0 focus:border-black" required>
                </div>

                <button type="submit" class="w-full h-[40px] bg-[#4FB5D0] font-bold text-white border-[1.5px] border-black hover:opacity-90 transition">
                    Create
                </button>
            </form>
        </div>

        <!-- Image Side (Order 1 on Mobile, Order 2 on Desktop) -->
        <div class="flex items-center justify-center w-full md:w-1/2 bg-white p-6 order-1 md:order-2">
            <img src="{{ asset('assets/images/umbrella.png') }}" alt="Fluffy Umbrella" class="w-3/4 md:w-auto h-auto md:h-60 object-contain">
        </div>
        
    </div>
</div>
