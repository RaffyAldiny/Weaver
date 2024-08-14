<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google Fonts: Lora and Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <!-- FilePond styles -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <title>Weaver - API Upload Service</title>
</head>
<body class="bg-[#FEFEFE] font-sans flex flex-col min-h-screen">
    <x-header />
    <main class="p-6 max-w-3xl mx-auto flex-grow">
        <h1 class="text-darkPurple text-4xl sm:text-5xl font-bold mb-6 drop-shadow-lg font-serif text-center">Upload Your Content</h1>
        
        <!-- Caption Input with Custom Emoji Picker -->
        <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf <!-- Laravel CSRF token for form security -->

            <div>
                <label for="caption" class="block text-darkPurple font-bold mb-2">Caption:</label>
                <div class="relative">
                    <textarea id="caption" name="caption" rows="4" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-mediumSlateBlue" placeholder="Enter your caption here..."></textarea>
                    <button type="button" id="emoji-button" class="absolute right-3 top-3 text-gray-500 hover:text-mediumSlateBlue focus:outline-none">😊</button>
                    <div id="emoji-picker" class="emoji-picker absolute bg-white border border-gray-300 rounded-md shadow-md mt-2 p-3 flex-wrap hidden z-50"></div>
                </div>
            </div>

            <!-- File Upload Section using FilePond -->
            <div>
                <input type="file" id="file" name="file" class="filepond" accept="video/*">
            </div>

            <!-- Video Preview Player -->
            <div id="video-preview-container" class="hidden mt-2">
                <video id="video-preview" class="w-full max-w-xs mx-auto rounded-md" controls></video>
            </div>

            <div>
                <button type="submit" class="w-full p-3 bg-[#121212] font-bold text-white rounded-md hover:bg-darkPurple focus:outline-none focus:ring-2 focus:ring-mediumSlateBlue">
                    Upload
                </button>
            </div>
        </form>
    </main>
    <footer class="bg-darkPurple text-white p-4 text-center mt-auto">
        <p>&copy; 2024 Weaver API Upload Service</p>
    </footer>

    <!-- FilePond scripts -->
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <!-- Custom Scripts -->
    <script src="{{ asset('js/weaver.js') }}"></script>
</body>
</html>
