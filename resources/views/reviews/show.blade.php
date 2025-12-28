@extends('layouts.dashboard')

@section('title', 'Reviews Show')

@section('content')
    <section>
        @csrf
        <p class="text-xl font-bold mb-4">Show Reviews</p>

        <div class="rounded-lg border border-gray-light p-8">
            <div class="flex items-center gap-4 text-black mb-8">
                <div class="w-32 h-32">
                    <img src="{{ asset($review->product->images[0]->image) }}" alt="" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <p class="text-xs">{{ date('d F Y', strtotime($review->header->date)) }}</p>
                    <p class="text-xl font-bold">{{ $review->product->name }}</p>
                    <p class="mb-4">Variants: {{ $review->variant->name }}</p>
                    <p>{{ $review->product->merchant->name }}</p>
                </div>
                <div class="ms-auto flex">
                    @for ($star = 0; $star < 5; $star++)
                        <div>
                            @if ($star < $review->review)
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-10 h-10 text-yellow-500 fill-yellow-500"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"
                                    />
                                </svg>
                            @else
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-10 h-10 text-gray-light fill-gray-light"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"
                                    />
                                </svg>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex items-center gap-16 mb-8">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Upload Image</p>
                    <x-form.label>File extension allowed: .JPG, .JPEG, .PNG</x-form.label>
                </div>

                <div class="flex-grow flex gap-8">
                    @foreach ($review->images as $image)
                        <div class="flex items-center justify-center w-1/5">
                            <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                <div class="flex flex-col items-center justify-center h-full w-full" id="div_review_image{{ $image }}">
                                    <img class="w-full h-full object-cover" src="{{ asset($image->image) }}" alt="">
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-16 mb-8">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Upload Video</p>
                    <x-form.label>File extension allowed: .MP4, .MOV</x-form.label>
                </div>

                <div class="flex-grow flex gap-8">
                    @foreach ($review->videos as $video)
                        <div class="flex items-center justify-center w-1/5">
                            <label class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                <div class="flex flex-col items-center justify-center h-full w-full" id="div_review_video{{ $video }}">
                                    <video src="{{ asset($video->video) }}" controls class="w-full h-full object-cover"></video>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-16">
                <div class="min-w-60 max-w-60">
                    <label for="message" class="text-gray font-semibold mb-1">Message</label>
                </div>
                <div class="flex-grow">
                    <x-form.textarea rows="3" id="message" name="message" required maxlength="255" disabled>{{ $review->message }}</x-form.textarea>
                </div>
            </div>
        </div>
    </section>
@endsection
