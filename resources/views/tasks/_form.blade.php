<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Task name<span class="text-red-500">*</span></label>
    <input type="text" name="name" id="name" value="{{ old('name', $task->name ?? '') }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" maxlength="255" required>
    @error('name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">
      Description
    </label>
    <textarea name="description" id="description" rows="3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('description', $task->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="priority" class="block text-sm font-medium text-gray-700">Priority <span class="text-red-500">*</span></label>
    <select name="priority" id="priority" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        <option value="low" {{ old('priority', $task->priority ?? '') == 'low' ? 'selected' : '' }}>low</option>
        <option value="medium" {{ old('priority', $task->priority ?? '') == 'medium' ? 'selected' : '' }}>medium</option>
        <option value="high" {{ old('priority', $task->priority ?? '') == 'high' ? 'selected' : '' }}>high</option>
    </select>
    @error('priority')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
    <select name="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        <option value="to-do" {{ old('status', $task->status ?? '') == 'to-do' ? 'selected' : '' }}>to do</option>
        <option value="in progress" {{ old('status', $task->status ?? '') == 'in progress' ? 'selected' : '' }}>in progress</option>
        <option value="done" {{ old('status', $task->status ?? '') == 'done' ? 'selected' : '' }}>done</option>
    </select>
    @error('status')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label for="due_date" class="block text-sm font-medium text-gray-700">Due date <span class="text-red-500">*</span></label>
    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', ($task->due_date ?? Carbon\Carbon::now())->format('Y-m-d')) }}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
    @error('due_date')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>