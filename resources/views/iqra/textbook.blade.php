<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $activeChapter ? $activeChapter->title : 'Digital Textbook' }} — IQRA</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --ink: #1f2430;
    --paper: #FFFDF7;
    --paper-line: #EDE6D6;
  }
  body { font-family: 'Inter', sans-serif; background: #0F172A; }
  .font-display { font-family: 'Baloo 2', sans-serif; }
  .font-mono { font-family: 'JetBrains Mono', monospace; }

  .page-texture {
    background-image: linear-gradient(var(--paper-line) 1px, transparent 1px);
    background-size: 100% 2.15rem;
    background-color: var(--paper);
  }
  .spine-tab {
    clip-path: polygon(0 0, 84% 0, 100% 50%, 84% 100%, 0 100%);
  }
  ::-webkit-scrollbar { width: 10px; height: 10px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
  .book-shadow {
    box-shadow: 0 2px 0 rgba(0,0,0,0.05), 0 25px 60px -20px rgba(0,0,0,0.55), 0 10px 20px -10px rgba(0,0,0,0.35);
  }
  [x-cloak] { display: none !important; }
</style>
</head>
<body class="min-h-screen text-slate-100">
<div x-data="textbookViewer()" x-init="init()" class="flex min-h-screen">

  <!-- SIDEBAR -->
  <aside class="w-[320px] shrink-0 bg-[#0F172A] border-r border-white/10 flex flex-col h-screen sticky top-0">
    <div class="px-6 pt-7 pb-5 border-b border-white/10 flex items-center justify-between">
      <div>
        <p class="font-mono text-[11px] tracking-[0.25em] text-emerald-400/80 uppercase">Pakistan School Boards</p>
        <h1 class="font-display text-2xl font-extrabold mt-1 leading-none text-white">IQRA <span class="text-emerald-400">Digital</span></h1>
        <p class="text-xs text-slate-400 mt-1">Digital Textbook &amp; Solved Exercises</p>
      </div>
      <a href="/" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center text-sm transition-all" title="Back to Control Dashboard">
        <i class="fa-solid fa-home"></i>
      </a>
    </div>

    <!-- Board picker -->
    <div class="px-4 pt-4 space-y-1.5 overflow-y-auto">
      <p class="px-2 text-[11px] font-mono uppercase tracking-widest text-slate-500 mb-1">Select your board</p>
      @foreach($boardGroups as $grp)
        <div>
          <button @click="toggleGroup('{{ $grp->slug }}')"
            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition"
            :class="openGroup === '{{ $grp->slug }}' ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5'">
            <span class="text-left">{{ $grp->name }}</span>
            <svg class="w-4 h-4 shrink-0 ml-2 transition-transform" :class="openGroup === '{{ $grp->slug }}' && 'rotate-90'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
          </button>
          <div x-show="openGroup === '{{ $grp->slug }}'" x-collapse class="pl-3 pr-1 pb-2 pt-1 space-y-1">
            @if($grp->boards->count() > 0)
              @foreach($grp->boards as $b)
                <a href="/{{ $grp->slug }}/{{ $b->slug }}"
                  class="w-full text-left text-xs px-3 py-2 rounded-md transition flex items-center gap-2 {{ ($activeBoard && $activeBoard->id === $b->id) ? 'bg-emerald-500/15 text-emerald-300 ring-1 ring-emerald-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}">
                  <span class="w-1.5 h-1.5 rounded-full bg-current shrink-0"></span>
                  <span>{{ $b->name }}</span>
                </a>
              @endforeach
            @else
              <p class="text-xs text-slate-500 italic px-3 py-2">Coming soon — notes for this board are being prepared.</p>
            @endif
          </div>
        </div>
      @endforeach
    </div>

    <!-- Unit spine tabs -->
    <div class="mt-4 border-t border-white/10 pt-4 px-2 flex-1 overflow-y-auto pb-6">
      <p class="px-4 text-[11px] font-mono uppercase tracking-widest text-slate-500 mb-2">
        {{ $activeClass ? $activeClass->name : 'Class 7' }} · Units
      </p>
      <div class="space-y-2 pr-2">
        @if(count($units) > 0)
          @foreach($units as $u)
            <a href="/{{ $activeGroup->slug }}/{{ $activeBoard->slug }}/{{ $activeClass->slug }}/{{ $activeSubject->slug }}/unit-{{ $u->chapter_number }}" class="w-full group block relative">
              <div class="spine-tab flex items-center gap-3 pl-4 pr-6 py-3 transition-all"
                   style="background: {{ ($activeUnitNumber == $u->chapter_number) ? ($u->color_hex ?: '#10B981') : 'rgba(255,255,255,0.04)' }}">
                <span class="font-display font-extrabold text-lg w-7 text-center rounded {{ ($activeUnitNumber == $u->chapter_number) ? 'text-white' : 'text-slate-400' }}">
                  {{ $u->chapter_number }}
                </span>
                <div class="text-left">
                  <p class="text-sm font-semibold leading-tight {{ ($activeUnitNumber == $u->chapter_number) ? 'text-white' : 'text-slate-300' }}">
                    {{ $u->title }}
                  </p>
                  <p class="text-[11px] {{ ($activeUnitNumber == $u->chapter_number) ? 'text-white/80' : 'text-slate-500' }}">
                    {{ $u->is_published ? 'Active Textbook' : 'Coming soon' }}
                  </p>
                </div>
              </div>
            </a>
          @endforeach
        @else
          <p class="text-xs text-slate-500 italic px-4 py-4">No subjects/units loaded for this board combination.</p>
        @endif
      </div>
    </div>

    <div class="px-6 py-4 border-t border-white/10 text-[11px] text-slate-500">
      Source notes digitised for classroom &amp; self-study use.
    </div>
  </aside>

  <!-- MAIN -->
  <main class="flex-1 h-screen overflow-y-auto">
    <div class="max-w-5xl mx-auto px-6 md:px-10 py-10">

      <!-- Board / breadcrumb bar -->
      <div class="flex items-center gap-2 text-xs font-mono text-slate-400 mb-6">
        <span>{{ $activeGroup ? $activeGroup->name : 'Pakistan Boards' }}</span>
        <svg class="w-3 h-3 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"/></svg>
        <span class="text-emerald-400">{{ $activeBoard ? $activeBoard->name : 'Select Board' }}</span>
        @if($activeClass)
          <svg class="w-3 h-3 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"/></svg>
          <span class="text-indigo-400">{{ $activeClass->name }}</span>
        @endif
        @if($activeSubject)
          <svg class="w-3 h-3 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"/></svg>
          <span class="text-pink-400">{{ $activeSubject->name }}</span>
        @endif
      </div>

      <!-- BOOK PANEL -->
      <div class="page-texture rounded-2xl book-shadow overflow-hidden text-[var(--ink)]">

        @if(!$activeChapter)
          <!-- Empty State when no content selected -->
          <div class="px-8 md:px-12 py-20 text-center">
            <div class="text-6xl mb-6">📚</div>
            <h3 class="font-display text-3xl font-extrabold text-slate-800">Select a Class &amp; Subject</h3>
            <p class="text-slate-500 mt-2 max-w-md mx-auto text-sm">Please navigate through a specific Board, Class, and Subject in the sidebar to open the corresponding digital textbook solved exercises.</p>
          </div>
        @else
          <!-- Chapter header band -->
          <div class="px-8 md:px-12 pt-10 pb-8 relative overflow-hidden" style="background: {{ $activeChapter->color_hex ?: '#10B981' }}">
            <p class="font-mono text-xs tracking-[0.3em] text-white/80 uppercase">Unit {{ $activeChapter->chapter_number }} of {{ count($units) }}</p>
            <h2 class="font-display text-white text-4xl md:text-5xl font-extrabold mt-2">{{ $activeChapter->title }}</h2>
            <p class="text-white/90 mt-2 max-w-xl text-sm md:text-base">{{ $activeChapter->blurb }}</p>
            <div class="absolute -right-6 -bottom-10 font-display font-extrabold text-[9rem] text-white/10 select-none">{{ $activeChapter->chapter_number }}</div>
          </div>

          @if(!$activeChapter->is_published && !isset($note))
            <div class="px-8 md:px-12 py-16 text-center">
              <div class="text-5xl mb-4">📘</div>
              <h3 class="font-display text-2xl font-bold text-slate-700">Notes for this unit are on the way</h3>
              <p class="text-slate-500 mt-2 max-w-md mx-auto text-sm">Only Unit 1 (Emerging Technologies) and a preview of Unit 2 (Digital Skills) have been digitised so far. Check back soon, or pick another unit from the sidebar.</p>
            </div>
          @else
            <!-- RENDER CONTENT WITH ALPINE -->
            <div class="px-6 md:px-12 py-8 space-y-4" x-data="{ openSection: '{{ count($structuredData['sections'] ?? []) > 0 ? $structuredData['sections'][0]['key'] : (isset($note) ? 'ocr_draft' : '') }}' }">

              <!-- SECTION NAV -->
              <div class="flex flex-wrap gap-2 mb-2">
                @foreach($structuredData['sections'] ?? [] as $sec)
                  <button @click="openSection = (openSection === '{{ $sec['key'] }}' ? '' : '{{ $sec['key'] }}')"
                    class="px-4 py-2 rounded-full text-xs font-semibold border transition"
                    :class="openSection === '{{ $sec['key'] }}' ? 'text-white border-transparent' : 'text-slate-600 border-slate-300 hover:border-slate-400'"
                    style="background: {{ ($activeChapter->color_hex ?: '#10B981') }};"
                    :style="openSection === '{{ $sec['key'] }}' ? '' : 'background:transparent'"
                    x-text="'{{ $sec['label'] }} ({{ $sec['count'] }})'">
                  </button>
                @endforeach
                @if(isset($note))
                  <button @click="openSection = (openSection === 'ocr_draft' ? '' : 'ocr_draft')"
                    class="px-4 py-2 rounded-full text-xs font-semibold border transition"
                    :class="openSection === 'ocr_draft' ? 'text-white border-transparent' : 'text-slate-600 border-slate-300 hover:border-slate-400'"
                    style="background: #6366F1;"
                    :style="openSection === 'ocr_draft' ? '' : 'background:transparent'"
                    x-text="'Parsed OCR Text (PDF)'">
                  </button>
                @endif
              </div>

              <!-- TICK EXERCISE -->
              @if(isset($structuredData['tick']))
                <section x-show="openSection === 'tick'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Tick (✓) the Correct Answer</h3>
                  <p class="text-xs text-slate-500 mb-4">Correct option is highlighted and marked.</p>
                  <div class="grid md:grid-cols-2 gap-3">
                    @foreach($structuredData['tick'] as $index => $q)
                      <div class="bg-white rounded-xl border border-slate-200 p-4">
                        <p class="text-sm font-semibold text-slate-800 mb-2">
                          <span class="font-mono text-slate-400 mr-1">{{ $index + 1 }}.</span>
                          <span>{{ $q['q'] }}</span>
                        </p>
                        <div class="flex flex-wrap gap-1.5">
                          @foreach($q['options'] as $oi => $opt)
                            <span class="text-xs px-2.5 py-1 rounded-md font-mono border {{ ($oi === $q['correct']) ? 'text-white border-transparent font-bold' : 'text-slate-500 border-slate-200' }}"
                              style="{{ ($oi === $q['correct']) ? 'background:' . ($activeChapter->color_hex ?: '#10B981') : '' }}">
                              {{ chr(97 + $oi) }}. {{ $opt }}
                            </span>
                          @endforeach
                        </div>
                      </div>
                    @endforeach
                  </div>
                </section>
              @endif

              <!-- BRIEF Q&A -->
              @if(isset($structuredData['brief']))
                <section x-show="openSection === 'brief'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Briefly Answer the Following Questions</h3>
                  <div class="space-y-3 mt-4">
                    @foreach($structuredData['brief'] as $index => $item)
                      <details class="bg-white rounded-xl border border-slate-200 group">
                        <summary class="px-4 py-3 flex items-start gap-3 cursor-pointer select-none">
                          <span class="shrink-0 w-6 h-6 rounded-full grid place-items-center text-white text-xs font-bold" style="background: {{ $activeChapter->color_hex ?: '#10B981' }}">
                            {{ $index + 1 }}
                          </span>
                          <span class="text-sm font-semibold text-slate-800 pt-0.5">{{ $item['q'] }}</span>
                          <svg class="w-4 h-4 ml-auto mt-1 text-slate-400 group-open:rotate-180 transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </summary>
                        <div class="px-4 pb-4 -mt-1">
                          <div class="pl-9 text-sm text-slate-600 leading-relaxed space-y-2">
                            @foreach($item['a'] as $p)
                              <p>{!! $p !!}</p>
                            @endforeach

                            @if(isset($item['table']))
                              <div class="overflow-x-auto mt-2 rounded-lg border border-slate-200">
                                <table class="w-full text-xs">
                                  <thead>
                                    <tr style="background: {{ $activeChapter->color_hex ?: '#10B981' }}" class="text-white">
                                      @foreach($item['table']['head'] as $h)
                                        <th class="px-3 py-2 text-left font-semibold">{{ $h }}</th>
                                      @endforeach
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($item['table']['rows'] as $ri => $row)
                                      <tr class="{{ ($ri % 2 !== 0) ? 'bg-slate-50' : 'bg-white' }}">
                                        @foreach($row as $cell)
                                          <td class="px-3 py-2 border-t border-slate-100 text-slate-600">{{ $cell }}</td>
                                        @endforeach
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            @endif
                          </div>
                        </div>
                      </details>
                    @endforeach
                  </div>
                </section>
              @endif

              <!-- DETAILED Q&A -->
              @if(isset($structuredData['detailed']))
                <section x-show="openSection === 'detailed'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Answer the Following Questions in Detail</h3>
                  <div class="space-y-3 mt-4">
                    @foreach($structuredData['detailed'] as $index => $item)
                      <details class="bg-white rounded-xl border border-slate-200 group">
                        <summary class="px-4 py-3 flex items-start gap-3 cursor-pointer select-none">
                          <span class="shrink-0 w-6 h-6 rounded-full grid place-items-center text-white text-xs font-bold" style="background: {{ $activeChapter->color_hex ?: '#10B981' }}">
                            {{ $index + 1 }}
                          </span>
                          <span class="text-sm font-semibold text-slate-800 pt-0.5">{{ $item['q'] }}</span>
                          <svg class="w-4 h-4 ml-auto mt-1 text-slate-400 group-open:rotate-180 transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </summary>
                        <div class="px-4 pb-4">
                          <div class="pl-9 text-sm text-slate-600 leading-relaxed space-y-2">
                            @foreach($item['a'] as $p)
                              <p>{!! $p !!}</p>
                            @endforeach

                            @if(isset($item['table']))
                              <div class="overflow-x-auto mt-2 rounded-lg border border-slate-200">
                                <table class="w-full text-xs">
                                  <thead>
                                    <tr style="background: {{ $activeChapter->color_hex ?: '#10B981' }}" class="text-white">
                                      @foreach($item['table']['head'] as $h)
                                        <th class="px-3 py-2 text-left font-semibold">{{ $h }}</th>
                                      @endforeach
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($item['table']['rows'] as $ri => $row)
                                      <tr class="{{ ($ri % 2 !== 0) ? 'bg-slate-50' : 'bg-white' }}">
                                        @foreach($row as $cell)
                                          <td class="px-3 py-2 border-t border-slate-100 text-slate-600">{{ $cell }}</td>
                                        @endforeach
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            @endif
                          </div>
                        </div>
                      </details>
                    @endforeach
                  </div>
                </section>
              @endif

              <!-- FUNCTIONS Q&A -->
              @if(isset($structuredData['functions']))
                <section x-show="openSection === 'functions'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Write the Functions of the Following</h3>
                  <div class="grid md:grid-cols-2 gap-3 mt-4">
                    @foreach($structuredData['functions'] as $index => $item)
                      <div class="bg-white rounded-xl border border-slate-200 p-4">
                        <p class="text-sm font-bold text-slate-800 mb-1.5 flex items-center gap-2">
                          <span class="w-5 h-5 rounded grid place-items-center text-white text-[10px] font-bold" style="background: {{ $activeChapter->color_hex ?: '#10B981' }}">
                            {{ $index + 1 }}
                          </span>
                          <span>{{ $item['q'] }}</span>
                        </p>
                        <p class="text-sm text-slate-600 leading-relaxed pl-7">{{ $item['a'] }}</p>
                      </div>
                    @endforeach
                  </div>
                </section>
              @endif

              <!-- MCQ -->
              @if(isset($structuredData['mcq']))
                <section x-show="openSection === 'mcq'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Objective Type Questions — MCQs</h3>
                  <p class="text-xs text-slate-500 mb-4">Knowledge, Understanding &amp; Analysis</p>
                  <div class="grid md:grid-cols-2 gap-3">
                    @foreach($structuredData['mcq'] as $index => $q)
                      <div class="bg-white rounded-xl border border-slate-200 p-4">
                        <p class="text-sm font-semibold text-slate-800 mb-2">
                          <span class="font-mono text-slate-400 mr-1">{{ $index + 1 }}.</span>
                          <span>{{ $q['q'] }}</span>
                        </p>
                        <div class="grid grid-cols-2 gap-1.5">
                          @foreach($q['options'] as $oi => $opt)
                            <span class="text-xs px-2 py-1 rounded-md font-mono border truncate {{ ($oi === $q['correct']) ? 'text-white border-transparent font-bold' : 'text-slate-500 border-slate-200' }}"
                              style="{{ ($oi === $q['correct']) ? 'background:' . ($activeChapter->color_hex ?: '#10B981') : '' }}"
                              title="{{ $opt }}">
                              {{ chr(97 + $oi) }}. {{ $opt }}
                            </span>
                          @endforeach
                        </div>
                      </div>
                    @endforeach
                  </div>
                </section>
              @endif

              <!-- CRQ -->
              @if(isset($structuredData['crq']))
                <section x-show="openSection === 'crq'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Constructed Response Questions (CRQs)</h3>
                  <p class="text-xs text-slate-500 mb-4">Give short answers to the following questions.</p>
                  <div class="space-y-3">
                    @foreach($structuredData['crq'] as $index => $item)
                      <details class="bg-white rounded-xl border border-slate-200 group">
                        <summary class="px-4 py-3 flex items-start gap-3 cursor-pointer select-none">
                          <span class="shrink-0 font-mono text-xs font-bold px-2 py-0.5 rounded text-white" style="background: {{ $activeChapter->color_hex ?: '#10B981' }}">
                            Q{{ $index + 1 }}
                          </span>
                          <span class="text-sm font-semibold text-slate-800">{{ $item['q'] }}</span>
                          <svg class="w-4 h-4 ml-auto mt-1 text-slate-400 group-open:rotate-180 transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </summary>
                        <div class="px-4 pb-4">
                          <div class="pl-2 text-sm text-slate-600 leading-relaxed space-y-2">
                            @foreach($item['a'] as $p)
                              <p>{!! $p !!}</p>
                            @endforeach

                            @if(isset($item['table']))
                              <div class="overflow-x-auto mt-2 rounded-lg border border-slate-200">
                                <table class="w-full text-xs">
                                  <thead>
                                    <tr style="background: {{ $activeChapter->color_hex ?: '#10B981' }}" class="text-white">
                                      @foreach($item['table']['head'] as $h)
                                        <th class="px-3 py-2 text-left font-semibold">{{ $h }}</th>
                                      @endforeach
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($item['table']['rows'] as $ri => $row)
                                      <tr class="{{ ($ri % 2 !== 0) ? 'bg-slate-50' : 'bg-white' }}">
                                        @foreach($row as $cell)
                                          <td class="px-3 py-2 border-t border-slate-100 text-slate-600">{{ $cell }}</td>
                                        @endforeach
                                      </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            @endif
                          </div>
                        </div>
                      </details>
                    @endforeach
                  </div>
                </section>
              @endif
              <!-- PARSED OCR TEXT DRAFT -->
              @if(isset($note))
                <section x-show="openSection === 'ocr_draft'" x-collapse x-cloak>
                  <h3 class="font-display text-xl font-bold mb-1">Parsed OCR Text (PDF Draft)</h3>
                  <p class="text-xs text-slate-500 mb-4">Raw text extracted via the background OCR pipeline.</p>
                  <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                    <pre class="whitespace-pre-wrap font-sans text-xs text-slate-700 leading-relaxed">{{ $note->extracted_text }}</pre>
                  </div>
                </section>
              @endif

              <div x-show="!openSection" class="text-center py-16 text-slate-400">
                <p class="text-sm">Pick a section above to open the chapter — Tick Exercise, Q&amp;A, MCQs, or CRQs.</p>
              </div>

            </div>
          @endif
        @endif
      </div>

      <p class="text-center text-slate-600 text-xs mt-6">Punjab Textbook Board · Computer Science for Class 7</p>
    </div>
  </main>
</div>

<script>
function textbookViewer() {
  return {
    openGroup: '{{ $activeGroup ? $activeGroup->slug : "punjab" }}',
    toggleGroup(slug) {
      this.openGroup = (this.openGroup === slug) ? '' : slug;
    },
    init() {
      // Initialize states or custom views
    }
  }
}
</script>
</body>
</html>
