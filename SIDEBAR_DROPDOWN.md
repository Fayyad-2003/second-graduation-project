# Sidebar Dropdown Component

## Usage

The sidebar dropdown component allows you to group similar menu items together in a collapsible dropdown.

### Basic Example

```blade
<x-sidebar-dropdown
    title="Menu Group Name"
    :active="request()->is('path/pattern*')"
    :icon="'<svg>...</svg>'">

    <a href="..." class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium">
        <span class="sidebar-text">Submenu Item</span>
    </a>

</x-sidebar-dropdown>
```

### Props

- `title` (string, required): The dropdown menu title
- `icon` (string, required): The SVG icon HTML
- `active` (boolean, optional): Whether the dropdown should be open by default

### Features

- Auto-opens when any child route is active
- Smooth collapse animation using Alpine.js
- Works with sidebar collapse/expand
- Maintains state during navigation

### Example from Student Sidebar

```blade
<x-sidebar-dropdown
    title="{{ __('AI Learning Tools') }}"
    :active="request()->is('students/study-plan-ai*', 'students/quiz-ai*')"
    :icon="'<svg class=\"w-[18px] h-[18px] flex-shrink-0 text-indigo-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.75\" d=\"M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253\"></path></svg>'">

    <a href="{{ url('students/study-plan-ai') }}"
        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/study-plan-ai*') ? 'active' : '' }}">
        <span class="sidebar-text">{{ __('Smart Study Plan') }}</span>
    </a>

    <a href="{{ url('students/quiz-ai') }}"
        class="sidebar-link flex items-center gap-3 px-4 py-2.5 rounded-xl text-primary-secondary text-sm font-medium {{ request()->is('students/quiz-ai*') ? 'active' : '' }}">
        <span class="sidebar-text">{{ __('AI Quizzes') }}</span>
    </a>

</x-sidebar-dropdown>
```
