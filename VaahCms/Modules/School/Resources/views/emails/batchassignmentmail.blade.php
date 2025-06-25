<p>Hey, {{ $teacher->name }}</p>
Welcome to our School

@if($teacher->batches && $teacher->batches->count() > 0)
    <p>You have been assigned to the following batches:</p>
    <ul>
        @foreach($teacher->batches as $batch)
            <div style="display: flex;">
                <li>{{ $batch->name ?? 'Batch #' . $batch->id }}</li>
                <span> - Timings: [{{ $batch->start_time }} - {{ $batch->end_time }} ]</span>
            </div>
        @endforeach
    </ul>
    <p>Thank You</p>
@else
    <p>You have not been assigned to any batches yet.</p>
    <p>Please wait for further instruction</p>
    <p>Thank You</p>
@endif