@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- ACTIVE PROJECTS -->
    <div class="relative overflow-hidden rounded-3xl
                bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                p-10 shadow-2xl text-white
                transition-all duration-300
                hover:scale-[1.03] hover:shadow-[0_20px_60px_rgba(37,94,199,0.4)]">

        <!-- Decorative Glow -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>

        <div class="flex items-center justify-between relative z-10">

            <!-- LEFT SIDE (Icon + Text) -->
            <div class="flex items-center space-x-5">
                
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md
                            rounded-2xl flex items-center justify-center
                            shadow-lg">
                    <i data-feather="briefcase" class="w-8 h-8"></i>
                </div>

                <div>
                    <p class="text-lg font-semibold opacity-90">
                        Active Projects
                    </p>
                    <p class="text-sm opacity-80">
                        Currently Ongoing
                    </p>
                </div>

            </div>

            <!-- RIGHT SIDE (Number) -->
            <div class="text-5xl font-extrabold tracking-tight">
                24
            </div>

        </div>
    </div>


    <!-- COMPLETED PROJECTS -->
    <div class="relative overflow-hidden rounded-3xl
                bg-gradient-to-r from-[#0A3562] via-[#255EC7] to-[#6999F1]
                p-10 shadow-2xl text-white
                transition-all duration-300
                hover:scale-[1.03] hover:shadow-[0_20px_60px_rgba(37,94,199,0.4)]">

        <!-- Decorative Glow -->
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>

        <div class="flex items-center justify-between relative z-10">

            <!-- LEFT SIDE -->
            <div class="flex items-center space-x-5">
                
                <div class="w-16 h-16 bg-white/20 backdrop-blur-md
                            rounded-2xl flex items-center justify-center
                            shadow-lg">
                    <i data-feather="check-circle" class="w-8 h-8"></i>
                </div>

                <div>
                    <p class="text-lg font-semibold opacity-90">
                        Completed Projects
                    </p>
                    <p class="text-sm opacity-80">
                        Successfully Delivered
                    </p>
                </div>

            </div>

            <!-- RIGHT SIDE -->
            <div class="text-5xl font-extrabold tracking-tight">
                156
            </div>

        </div>
    </div>

</div>

<script>
    feather.replace()
</script>

@endsection