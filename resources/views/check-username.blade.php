<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Username</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Check Social Media Username</h2>
    
    <form action="{{ route('check.username') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="platform" class="form-label">Platform</label>
            <select name="platform" id="platform" class="form-select" required>
                <option value="">Select Platform</option>
                <option value="facebook" {{ old('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                <option value="twitter" {{ old('platform') == 'twitter' ? 'selected' : '' }}>Twitter</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Check Username</button>
    </form>

    @if (isset($result['status']))
    <div class="alert mt-4 {{ strpos($result['status'], 'exists') !== false ? 'alert-success' : 'alert-danger' }}">
        {{ $result['status'] ??'' }}
        <br>
        {{ $result['platform']??''}}
        <br>
        {{ $result['name']??''}}
        <br>
        {{ $result['message'] ?? '' }}
    </div>

    {{-- Check if metadata exists and display it --}}
    @if (isset($result['metadata']) && is_array($result['metadata']))
            <div class="mt-3">
                <h4>Metadata:</h4>
                <ul>
                    <li><strong>Title:</strong> {{ $result['metadata']['title'] ?? 'N/A' }}</li>
                    <li><strong>Description:</strong> {{ $result['metadata']['description'] ?? 'N/A' }}</li>
                    <li><strong>Image URL:</strong> 
                        @if (!empty($result['metadata']['image']))
                            <a href="{{ $result['metadata']['image'] }}" target="_blank">View Image</a>
                        @else
                            N/A
                        @endif
                    </li>
                </ul>
            </div>
        @endif
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
