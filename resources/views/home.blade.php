@extends('layouts.app')

@section('styles')
    <style>
        /* Container styles */
        .message-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Message card styles */
        .message-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }

        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .message-content {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #2c3e50;
        }

        .message-footer {
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 1px solid #eee;
        }

        /* User info styles */
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #4a90e2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Button styles */
        .delete-button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .delete-button:hover {
            background: #c82333;
        }

        /* Alert styles */
        .alert {
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        /* Sticky compose form styles */
        .sticky-compose {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sticky-compose-inner {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .compact-textarea {
            flex-grow: 1;
            min-height: 45px !important;
            max-height: 120px;
            padding: 0.5rem 1rem !important;
            margin-bottom: 0 !important;
            resize: none;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .compact-textarea:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
        }

        .messages-container {
            margin-bottom: 100px;
        }

        .compact-button {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 0.5rem 1.5rem !important;
            height: 45px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .compact-button:hover {
            background: #357abd;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
    <div class="message-container">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Messages List -->
        <div class="messages-container">
            @foreach ($messages as $message)
                <div class="message-card">
                    <div class="card-body p-4">
                        <div class="message-content mb-4">
                            {{ $message->content }}
                        </div>

                        <div class="message-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($message->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $message->user->name }}</div>
                                        <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>

                                @if (auth()->id() === $message->user_id)
                                    <form action="{{ route('messages.destroy', $message->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="delete-button"
                                                onclick="return confirm('Are you sure you want to delete this message?')"
                                        >
                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Compose Form -->
        <div class="sticky-compose">
            <div class="sticky-compose-inner">
                <form method="POST" action="{{ route('messages.store') }}" class="d-flex w-100 gap-3">
                    @csrf
                    <textarea
                        name="content"
                        class="form-control compact-textarea @error('content') is-invalid @enderror"
                        placeholder="Write a message..."
                        required
                    >{{ old('content') }}</textarea>
                    <button type="submit" class="compact-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Autosize textarea
        const textarea = document.querySelector('.compact-textarea');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            const newHeight = Math.min(this.scrollHeight, 120); // Maximum height of 120px
            this.style.height = newHeight + 'px';
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const dismissButton = alert.querySelector('.btn-close');
                if (dismissButton) {
                    dismissButton.click();
                }
            });
        }, 5000);

        // Focus textarea when pressing / key
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && !e.target.matches('textarea, input')) {
                e.preventDefault();
                textarea.focus();
            }
        });
    </script>
@endsection
