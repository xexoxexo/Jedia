@extends('layouts.dashboard')

@section('title', 'Reviews Create')

@section('content')
    <form action="{{ route('reviews.store', ['th_id' => $td->transaction_id, 'pr_id' => $td->product_id, 'va_id' => $td->variant_id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <p class="text-xl font-bold mb-4">Add Reviews</p>

        <div class="rounded-lg border border-gray-light p-8">
            <div class="flex items-center gap-4 text-black mb-8">
                <div class="w-32 h-32">
                    <img src="{{ asset($td->product->images[0]->image) }}" alt="" class="w-full h-full object-cover rounded-lg">
                </div>
                <div>
                    <p class="text-xs">{{ date('d F Y', strtotime($td->header->date)) }}</p>
                    <p class="text-xl font-bold">{{ $td->product->name }}</p>
                    <p class="mb-4">Variants: {{ $td->variant->name }}</p>
                    <p>{{ $td->product->merchant->name }}</p>
                </div>
                <div class="ms-auto">
                    @for ($star = 0; $star < 5; $star++)
                        <button type="button" value="{{ $star }}" class="button-stars" onclick="changeStars(this.value)">
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
                        </button>
                    @endfor
                    <input type="hidden" id="review" name="review" id="review" min="1" max="5" value="1" required>
                </div>
            </div>

            <div class="flex items-center gap-16 mb-8">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Upload Image</p>
                    <x-form.label>File extension allowed: .JPG, .JPEG, .PNG</x-form.label>
                </div>

                <div class="flex-grow flex gap-8">
                    @for ($image = 1; $image <= 5; $image++)
                        <div class="flex items-center justify-center w-1/5">
                            <label for="review_image{{ $image }}" class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                <div class="flex flex-col items-center justify-center h-full w-full" id="div_review_image{{ $image }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <p class="text-sm">Image {{ $image }}</p>
                                </div>
                                <input id="review_image{{ $image }}" name="review_images[]" type="file" class="hidden" accept=".jpg,.jpeg,.png" onchange="validateBoxImage(this, 'div_review_image{{ $image }}')"/>
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex items-center gap-16 mb-8">
                <div class="min-w-60 max-w-60">
                    <p class="text-gray font-semibold mb-1">Upload Video</p>
                    <x-form.label>File extension allowed: .MP4, .MOV</x-form.label>
                </div>

                <div class="flex-grow flex gap-8">
                    @for ($video = 1; $video <= 5; $video++)
                        <div class="flex items-center justify-center w-1/5">
                            <label for="review_video{{ $video }}" class="flex flex-col items-center justify-center w-full h-36 border-2 border-gray-light border-dashed rounded-lg cursor-pointer text-gray">
                                <div class="flex flex-col items-center justify-center h-full w-full" id="div_review_video{{ $video }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <p class="text-sm">Video {{ $video }}</p>
                                </div>
                                <input id="review_video{{ $video }}" name="review_videos[]" type="file" class="hidden" accept=".mp4,.mov" onchange="validateBoxVideo(this, 'div_review_video{{ $video }}')"/>
                            </label>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex items-center gap-16 mb-8">
                <div class="min-w-60 max-w-60">
                    <label for="message" class="text-gray font-semibold mb-1">Message <span class="text-red font-bold">*</span></label>
                </div>
                <div class="flex-grow">
                    <x-form.textarea rows="3" id="message" name="message" required maxlength="255"></x-form.textarea>
                </div>
            </div>

            <x-button variant="primary" type="submit">Insert Review</x-button>
            <x-form.text>You are required to give the stars before submit.</x-form.text>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function changeStars(idx)
        {
            document.querySelectorAll(".button-stars").forEach((element, index) => {
                if (index <= idx) {
                    element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-yellow-500 fill-yellow-500"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>`;
                } else {
                    element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-gray-light fill-gray-light"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>`;
                }
            });

            $('#review').val(parseInt(idx) + 1);
        }
    </script>
@endpush
