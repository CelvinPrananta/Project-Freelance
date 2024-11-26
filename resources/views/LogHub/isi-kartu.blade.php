<div class="cover-card card-cover2-{{ $isianKartu->pattern }} {{ $isianKartu->pattern ? '' : 'hiddens' }}"
    id="cover-card2-{{ $isianKartu->id }}" style="height: 116px !important;"></div>
<div class="modal-header" style="justify-content: left;">
    <div class="icon-card">
        <i class="fa-solid fa-credit-card fa-lg"></i>
    </div>
    <div>
        <h5 class="nama-kartu" data-kartu-id="{{ $isianKartu->id }}">{{ $isianKartu->name }}</h5>
        <div class="info-status4">
            <div class="dropdown dropdown-action opsi-hapus-cover" id="opsi-hapus-cover{{ $isianKartu->id }}">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-left">
                    <a href="#" class="dropdown-item card-cover4-{{ $isianKartu->pattern }}"
                        data-toggle="dropdown" data-auto-close="outside" id="formCover-{{ $isianKartu->id }}"
                        aria-expanded="false"><i class="fa-solid fa-clapperboard m-r-5"></i><span
                            id="coverText">{{ $isianKartu->pattern ? 'Change Cover' : 'Add Cover' }}</span></a>
                    <form id="updateCoverForm" class="dropdown-menu p-4"
                        style="min-width: 16rem !important; margin-top: -37px !important; margin-left: -19px !important;">
                        @csrf
                        <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                        <div class="topnav-dropdown-header">
                            <span class="move-card">Cover</span>
                        </div><br>
                        <div class="flex flex-col w-full gap-2">
                            <label
                                style="font-size: 18px;  @if ($result_tema->tema_aplikasi == 'Gelap') color: white; @endif">Color's</label>
                            <small class="text-danger" style="font-weight: 700; font-size: 13px;">*Please select
                                (Color's) again when updating.</small>
                            <input type="hidden" id="cover-field-{{ $isianKartu->id }}" name="pattern"
                                value="{{ isset($covers[0]) ? $covers[0] : 'default_value' }}">
                            <div
                                class="flex flex-wrap items-center justify-start w-full max-w-2xl gap-2 px-4 py-2 overflow-auto border-2 border-gray-200 h-36 rounded-xl">
                                @isset($covers)
                                    @foreach ($covers as $cover)
                                        <div onclick="selectPattern2('{{ $cover }}', '{{ $isianKartu->id }}')"
                                            class="{{ $cover == $covers[0] ? 'order-first' : '' }} h-full flex flex-wrap border-4 rounded-lg w-36 card-cover-{{ $cover }} hover:border-black"
                                            id="cover-{{ $cover }}-{{ $isianKartu->id }}"
                                            style="height: 40% !important; width: 5rem !important; cursor: pointer">
                                            <div id="check-{{ $cover }}-{{ $isianKartu->id }}"
                                                class="flex flex-wrap items-center justify-center w-full h-full {{ $cover == $covers[0] ? 'opacity-100' : 'opacity-0' }}">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </form>
                        <a href="#"
                            class="dropdown-item card-cover3-{{ $isianKartu->pattern }} {{ $isianKartu->pattern ? '' : 'hiddens' }}"
                            id="cover-card3-{{ $isianKartu->id }}" data-toggle="modal">
                            <form onclick="hapusCoverCard('{{ $isianKartu->id }}', event)">
                                @csrf
                                <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                <button type="submit" class="deleteCover"
                                    style=" @if ($result_tema->tema_aplikasi == 'Gelap') color: white; @endif ">
                                    <i class='fa fa-trash-o m-r-5'></i> Delete Cover
                                </button>
                            </form>
                        </a>
                        @include('script.addCoverCard')
                        <a href="#" class="dropdown-item"
                            onclick="deleteCardModal('{{ $isianKartu->id }}', '{{ $isianKartu->name }}', '{{ $dataKolom->name }}', '{{ route('hapusKartu', ['card_id' => $isianKartu->id]) }}');">
                            <i class='fa fa-trash-o m-r-5'></i> Delete Card
                        </a>
                        @php
                            $softDeletedTitle = $isianKartu->titleChecklists()->onlyTrashed()->count();
                            $softDeletedChecklist = \App\Models\Checklists::onlyTrashed()
                                ->whereHas('titleChecklist', function ($query) use ($isianKartu) {
                                    $query->where('cards_id', $isianKartu->id);
                                })
                                ->count();
                            $displayStyle = $softDeletedTitle > 0 || $softDeletedChecklist > 0 ? '' : 'display: none;';
                        @endphp
                        <a href="#" onclick="recoverTitleChecklistModal('{{ $isianKartu->id }}')"
                            class="dropdown-item recover-title-checklist"
                            id="recover-title-checklist-{{ $isianKartu->id }}" data-card-id="{{ $isianKartu->id }}"
                            style="{{ $displayStyle }}">
                            <i class="fa-solid fa-recycle m-r-5"></i> Recover Title / Checklist
                        </a>
                        @include('script.showModalRecoverTitleandChecklist')
                    </div>
                </div>
                <span class="text-status4" style="line-height: 20px"><b>Delete / Cover</b></span>
            </div>
            <!-- /Untuk Pembaharuan Cover dan Hapus Kartu -->

            {{-- @endif --}}
            <div class="aksi-move-card">
                <p class="tag-list">in list</p>
                <div class="dropdown info-status10">
                    <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false"
                        data-auto-close="outside" data-kolom-id="{{ $dataKolom->id }}"
                        style="color: #489bdb;  @if ($result_tema->tema_aplikasi == 'Gelap') color: #489bdb !important; @endif  margin-left: -12px; text-decoration: underline;">{{ $dataKolom->name }}</a>
                    <span class="text-status10"><b>Move Card's</b></span>
                    <form class="dropdown-menu p-4" style="min-width: 18rem !important; margin-left: 34px !important;">
                        <div class="topnav-dropdown-header">
                            <span class="move-card">Move Card</span>
                        </div><br>
                        <div class="form-group">
                            <label for="select-card" @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif>List
                                Card</label>
                            <select onclick="changeCard('{{ $isianKartu->id }}')" id="select-card{{ $isianKartu->id }}"
                                class="theSelect"
                                style="width: 100% !important; height: 36px; @if ($result_tema->tema_aplikasi == 'Gelap') color: white; background-color: #292D3E; @endif  cursor:pointer">
                                <option selected disabled>-- Select Card --</option>
                                @foreach ($dataKolom->cards->sortBy('id') as $dataKartu)
                                    <option value="{{ $dataKartu->id }}">{{ $dataKartu->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <div class="info-status13">
                <span class="text-status13"><b>ESC (Close Modal)</b></span>
                <span aria-hidden="true">&times;</span>
            </div>
        </button>
    </div>
    <div class="modal-body">
        <!-- Tambah & Edit Keterangan -->
        <div class="menu-keterangan">
            <div class="icon-align">
                <i class="fa-solid fa-align-left fa-lg"></i>
            </div>
            <div class="keterangan-tag">
                <p class="deskripsi-keterangan">Description</p>
                <form id="myForm{{ $isianKartu->id }}" method="POST">
                    @csrf
                    <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                    <textarea onclick="mentionTags2('keterangan{{ $isianKartu->id }}')" class="isian-deskripsi" rows="4"
                        id="keterangan{{ $isianKartu->id }}" name="keterangan" placeholder="Enter a description">{{ $isianKartu->description }}</textarea><br>
                    <div class="mention-tag-keterangan" id="mention-tag-keterangan{{ $isianKartu->id }}"></div>
                    <div class="aksi-update-keterangan gap-2">
                        <button type="submit" class="btn btn-outline-info icon-keterangan hidden"
                            id="saveButton{{ $isianKartu->id }}">Save</button>
                        <button type="button" class="btn btn-outline-danger icon-keterangan hidden"
                            id="cancelButton{{ $isianKartu->id }}">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @include('script.addDescription')
        <!-- /Tambah & Edit Keterangan -->

        <!-- Tambah Judul Checklist -->
        <form id="myFormTitle{{ $isianKartu->id }}" method="POST">
            @csrf
            <div class="menu-tambah-checklist flex flex-col flex-wrap">
                <div class="header-checklist flex gap-5">
                    <div class="icon-tambah-checklist hidden" id="iconCheck-{{ $isianKartu->id }}">
                        <i class="fa-regular fa-square-check fa-xl" style="color: #489bdb;"></i>
                    </div>
                    <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                    <input type="text" class="border border-1 border-dark w-40l p-2 rounded-xl hidden"
                        id="titleChecklist{{ $isianKartu->id }}" name="titleChecklist" placeholder="Enter a title"
                        required>
                </div>
                <div class="aksi-tambah-checklist gap-2">
                    <button type="submit" class="btn btn-outline-info icon-keterangan hidden"
                        id="saveButtonTitle{{ $isianKartu->id }}">
                        <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                            aria-hidden="true">
                        </span>
                        Save</button>
                    <button type="button" class="btn btn-outline-danger icon-keterangan hidden"
                        id="cancelButtonTitle{{ $isianKartu->id }}">Cancel</button><br>
                </div>
            </div>
        </form>
        @include('script.addTitle')
        <!-- Membuat Template Judul Checklist -->
        <div class="template-title">
            <form id="makeTemplateForm{{ $isianKartu->id }}" method="POST">
                @csrf
                <input type="hidden" id="cards_id" name="cards_id" value="{{ $isianKartu->id }}">
                <input type="hidden" name="name[]" value="TARGET MINGGUAN">
                <input type="hidden" name="name[]" value="SENIN">
                <input type="hidden" name="name[]" value="SELASA">
                <input type="hidden" name="name[]" value="RABU">
                <input type="hidden" name="name[]" value="KAMIS">
                <input type="hidden" name="name[]" value="JUM'AT">
                <button type="submit" class="btn btn-outline-info icon-keterangan hidden"
                    id="makeTemplate{{ $isianKartu->id }}">
                    <div class="info-status20">
                        <i class="fa-solid fa-book"></i> Make Template
                        <span class="text-status20">
                            <b>DATA TEMPLATE :<br>1. TARMING <br>2. SENIN <br>3. SELASA <br>4. RABU <br>5. KAMIS <br>6.
                                JUM'AT</b>
                        </span>
                    </div>
                </button>
            </form>
        </div>
        @include('script.addTemplateTitle')
        <!-- /Membuat Template Judul Checklist -->

        <div class="tambah-checklist">
            <button type="button" class="btn btn-outline-info icon-item add-title" id="addTitle"
                data-id="{{ $isianKartu->id }}"><i class="fa-regular fa-square-check fa-lg"></i> Add Title</button>
        </div>
        <!-- /Tambah Judul Checklist -->

        <div class="title-container" id="titleContainer">
            @php
                $sortedDataTitle =
                    $isianKartu->titleChecklists->count() > 0
                        ? $isianKartu->titleChecklists->sortBy(function ($item) {
                            return $item->position == 0 ? $item->id : $item->position;
                        })
                        : $isianKartu->titleChecklists->sortBy('id');
            @endphp
            @foreach ($sortedDataTitle as $titleChecklists)
                <div class="menu-checklist border border-1 border-darkss p-2 rounded-xl"
                    data-id="{{ $titleChecklists->id }}">
                    <!-- Perbaharui & Hapus Judul Checklist -->
                    <div class="header-checklist flex justify-content">
                        <i class="fa-regular fa-square-check fa-xl"
                            style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                        <form id="myFormTitleUpdate{{ $titleChecklists->id }}" method="POST" class="update-title">
                            @csrf
                            <input type="hidden" id="title_id" name="title_id" value="{{ $titleChecklists->id }}">
                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                            <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl"
                                style="font-size: 17px" id="titleChecklistUpdate{{ $titleChecklists->id }}"
                                name="titleChecklistUpdate" data-id="{{ $titleChecklists->id }}"
                                placeholder="Enter a title" value="{{ $titleChecklists->name }}">
                            <div class="aksi-update-title gap-2">
                                <button type="submit" class="btn btn-outline-info icon-keterangan hidden"
                                    id="saveButtonTitleUpdate{{ $titleChecklists->id }}">
                                    <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                                        aria-hidden="true">
                                    </span>
                                    Save</button>
                                <button type="button" class="btn btn-outline-danger icon-keterangan hidden"
                                    id="cancelButtonTitleUpdate{{ $titleChecklists->id }}">Cancel</button>
                            </div>
                        </form>
                        {{-- @if ($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                        <form id="myFormTitleDelete{{ $titleChecklists->id }}" method="POST">
                            @csrf
                            <input type="hidden" id="id" name="id" value="{{ $titleChecklists->id }}">
                            <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                            <div class="icon-hapus-title" id="hapus-title{{ $titleChecklists->id }}">
                                <button type="submit" style="border: none; background: none; padding: 0;">
                                    <div class="info-status5">
                                        <i class="fa fa-trash-o icon-trash"
                                            @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif></i>
                                        <span class="text-status5"><b>Delete Title's</b></span>
                                    </div>
                                </button>
                            </div>
                        </form>
                        {{-- @endif --}}
                    </div>
                    <!-- /Perbaharui & Hapus Judul Checklist -->

                    <!-- Progress Bar Checklist -->
                    @php
                        $hasChecklists = $titleChecklists->checklists->isNotEmpty();
                        $titleChecklistsPercentage = $titleChecklists->percentage;
                    @endphp
                    {{-- @dd($titleChecklists->percentage) --}}
                    <div class="checkall-progress" id="checkall-progress-{{ $titleChecklists->id }}">
                        <div class="checklist-all gap-2" id="checklist-all-{{ $titleChecklists->id }}">
                            <form action="{{ route('perbaharuiSemuaChecklist') }}"
                                id="checklistAllForm{{ $titleChecklists->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="title_checklists_id" value="{{ $titleChecklists->id }}">
                                <div class="info-status21">
                                    <input type="checkbox"
                                        class="checklistform-all @if (!$hasChecklists) hidden @endif
                                    "
                                        name="checklistform-all" id="checklistform-all-{{ $titleChecklists->id }}"
                                        data-id="{{ $titleChecklists->id }}"
                                        @if ($titleChecklistsPercentage == 100) checked @endif>
                                    <span class="text-status21">
                                        <b>Check All</b>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="progress" data-checklist-id="{{ $titleChecklists->id }}">
                            <div class="progress-bar progress-bar-{{ $titleChecklists->id }}" role="progressbar"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                {{ $titleChecklists->percentage }}
                            </div>
                        </div>
                    </div>
                    <!-- Progress Bar Checklist -->

                    <!-- Perbaharui & Hapus Checklist -->
                    <div class="checklist-container" id="checklist-container-{{ $titleChecklists->id }}"
                        data-id="{{ $titleChecklists->id }}">
                        @include('script.progressBarChecklist')
                        @php
                            $sortedDataChecklist =
                                $titleChecklists->checklists->count() > 0
                                    ? $titleChecklists->checklists->sortBy(function ($item) {
                                        return $item->position == 0 ? $item->id : $item->position;
                                    })
                                    : $titleChecklists->checklists->sortBy('id');
                        @endphp

                        @foreach ($sortedDataChecklist as $checklists)
                            <div id="section-checklist-{{ $checklists->id }}" class="input-checklist"
                                data-id="{{ $checklists->id }}">
                                <!-- Tampilan Checklist -->
                                <form id="myFormChecklistUpdate{{ $checklists->id }}" method="POST"
                                    class="form-checklist">
                                    @csrf
                                    <input class="dynamicCheckbox" type="checkbox" id="{{ $checklists->id }}"
                                        name="{{ $checklists->id }}"
                                        {{ $checklists->is_active == '1' ? 'checked' : '' }}>
                                    <label onclick="mentionTags4('checkbox-{{ $checklists->id }}')"
                                        class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl {{ $checklists->is_active == '1' ? 'strike-through' : '' }}"
                                        id="labelCheckbox-{{ $checklists->id }}"
                                        for="labelCheckbox-{{ $checklists->id }}">{{ $checklists->name }}</label>
                                    <input type="hidden" id="checklist_id" name="checklist_id"
                                        value="{{ $checklists->id }}">
                                    <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                    <input type="text"
                                        class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden"
                                        id="checkbox-{{ $checklists->id }}" name="checkbox-{{ $checklists->id }}"
                                        value="{{ $checklists->name }}" placeholder="Enter a checklist">
                                    <div class="mention-tag" id="mention-tag-checkbox{{ $checklists->id }}"></div>

                                    <!-- Aksi Update Checklist -->
                                    <div onclick="checklistUpdate({{ $checklists->id }})"
                                        class="aksi-update-checklist2 gap-2 margin-bottom-0"
                                        id="checklist-{{ $checklists->id }}">
                                        <button type="submit" class="saves btn btn-outline-info hidden"
                                            id="saveButtonChecklistUpdate-{{ $checklists->id }}">
                                            <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                                                aria-hidden="true">
                                            </span>
                                            Save</button>
                                        <button type="button" class="cancels btn btn-outline-danger hidden"
                                            id="cancelButtonChecklistUpdate-{{ $checklists->id }}">Cancel</button>
                                    </div>
                                    <!-- /Aksi Update Checklist -->
                                </form>
                                <!-- Icon Hapus Checklist -->
                                {{-- @if ($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                <form id="myFormChecklistDelete{{ $checklists->id }}" method="POST">
                                    @csrf
                                    <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                                    <input type="hidden" id="title_checklists_id" name="title_checklists_id"
                                        value="{{ $titleChecklists->id }}">
                                    <input type="hidden" id="id" name="id" value="{{ $checklists->id }}">
                                    <div class="icon-hapus-checklist" id="hapus-checklist{{ $checklists->id }}">
                                        <button type="submit" class="deletes"
                                            id="deleteButtonChecklist-{{ $checklists->id }}"
                                            style="border: none; background: none; padding: 0;">
                                            <div class="info-status6">
                                                <i class="fa fa-trash-o icon-trash"
                                                    @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif></i>
                                                <span class="text-status6"><b>Delete Checklist</b></span>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                                {{-- @endif --}}
                                <!-- /Icon Hapus Checklist -->
                            </div>
                            <!-- /Tampilan Checklist -->
                        @endforeach
                    </div>
                    <!-- /Perbaharui & Hapus Checklist -->

                    <!-- Tambah baru checklist -->
                    <form id="myFormChecklist{{ $titleChecklists->id }}" method="POST">
                        @csrf
                        <input type="hidden" id="title_id" name="title_id" value="{{ $titleChecklists->id }}">
                        <input type="hidden" id="card_id" name="card_id" value="{{ $isianKartu->id }}">
                        <div class="header-tambah-checklist flex gap-4">
                            <i class="fa-xl"></i>
                            <input type="text"
                                class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden"
                                id="checklist{{ $titleChecklists->id }}" name="checklist"
                                placeholder="Enter a checklist" required>
                            <div class="mention-tag" id="mention-tag-checklist{{ $titleChecklists->id }}"></div>
                        </div>
                        <div class="aksi-update-checklist gap-2">
                            <button type="submit" class="btn btn-outline-info icon-keterangan hidden"
                                id="saveButtonChecklist{{ $titleChecklists->id }}">
                                <span class="spinner-border spinner-border-sm d-none mr-2 mb-1" role="status"
                                    aria-hidden="true"></span>

                                Save</button>
                            <button type="button" class="btn btn-outline-danger icon-keterangan hidden"
                                id="cancelButtonChecklist{{ $titleChecklists->id }}">Cancel</button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-outline-info add-checklist"
                        id="AddChecklist
                        {{-- {{ $titleChecklists->id }} --}}
                        "
                        onclick="mentionTags('checklist{{ $titleChecklists->id }}')"
                        data-id="{{ $titleChecklists->id }}" data-persen="{{ $titleChecklists->percentage }}"><i
                            class="fa-solid fa-plus" aria-hidden="true"></i> Add
                        an Item...</button>
                    <!-- Tambah baru checklist -->
                </div>
            @endforeach
        </div>

        {{-- <div class="menu-activity">
            <div class="header-activity flex">
                <i class="fa-solid fa-list-ul fa-lg"></i>
                <p class="activity-keterangan">Activity </p>
                <div onclick="showActivity('{{ $isianKartu->id }}')" class="icon-lihat">
                    <div class="info-status7">
                        <i class="fa-solid fa-eye fa-lg icon-comment" id="showActivityIcon{{ $isianKartu->id }}"></i>
                        <span class="text-status7"><b>Seeing Comment's</b></span>
                    </div>
                </div>
            </div>
            <div class="input-komentar flex gap-4">
                <img class="avatar-comment" src="{{ URL::to('/assets/images/' . Auth::user()->avatar) }}"
                    loading="lazy">
                <form id="commentForm{{ $isianKartu->id }}"
                    action="{{ route('komentarKartu', ['card_id' => encrypt($isianKartu->id)]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="card_id" value="{{ $isianKartu->id }}">
                    <textarea onclick="saveComment('{{ $isianKartu->id }}'); mentionTags3('contentarea{{ $isianKartu->id }}')"
                        class="form-control border border-1 border-dark rounded-xl" rows="1" cols="77"
                        id="contentarea{{ $isianKartu->id }}" name="content" placeholder="Write a comment..."></textarea>
                    <div class="mention-tag-comment" id="mention-tag-comment{{ $isianKartu->id }}"></div>
                    <button type="submit" class="btn btn-outline-info hidden" style="margin-top: 1%;"
                        id="simpanButton{{ $isianKartu->id }}">Save</button>
                </form>

            </div>
            <div class="activity-tag hiddens" id="showActivity{{ $isianKartu->id }}">
                @php
                    $columns = DB::table('columns')
                        ->where('id', $dataKolom->id)
                        ->get();
                    $cards = DB::table('cards')
                        ->where('column_id', $dataKolom->id)
                        ->where('id', $isianKartu->id)
                        ->get();
                @endphp
                @foreach ($columns as $column)
                    @foreach ($cards as $card)
                        @php
                            $isianHistory = DB::table('card_histories')
                                ->leftjoin('users', 'card_histories.user_id', 'users.id')
                                ->where('card_id', $card->id)
                                ->select('card_histories.*', 'users.id', 'users.name', 'users.avatar')
                                ->orderBy('card_histories.created_at', 'desc')
                                ->get();
                        @endphp
                        @foreach ($isianHistory as $history)
                            <div class="isian-tag" id="isianTag{{ $isianKartu->id }}">

                                <div class="isian-history">
                                    <img class="avatar-activity"
                                        src="{{ URL::to('/assets/images/' . $history->avatar) }}" loading="lazy">
                                    <div class="title-activity">
                                        <p class="isian-activity">
                                            {{ $history->name }}<br><span>{{ $history->content }}</span></p>
                                    </div>
                                </div>
                                <div class="waktu-history">
                                    <p class="isian-activity"><i class="fa-solid fa-clock" style="color: #808080;"
                                            aria-hidden="true"></i>
                                        {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('j F \p\u\k\u\l h:i A') }}
                                    </p>
                                </div>

                            </div>
                        @endforeach
                    @endforeach
                @endforeach
            </div>
        </div> --}}
    </div>

    @include('script.moveTitle')
    @include('script.addMentiontag')
    {{-- @include('script.addComment') --}}

    {{-- NEW CHECK ALL BIAR GA DI FOREACH 1 1 --}}
    <script>
        $(document).ready(function() {
            // // Tandai untuk mencegah pengiriman berulang kali
            // let isSubmitting = false;
            $(document).on('change', '.checklistform-all', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const title_id = $(this).data('id');
                isSubmitting = true;
                var checklistId = $(this).closest('form').find(
                    'input[name="title_checklists_id"]').val();
                var isChecked = $(this).is(':checked');
                var toastBerhasil = isChecked ? 'Berhasil centang semua checklist!' :
                    'Berhasil tidak centang semua checklist!';

                $.ajax({
                    url: $(this).closest('form').attr('action'),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        title_checklists_id: checklistId,
                        is_active: isChecked ? 1 : 0
                    },
                    success: function(response) {
                        toastr.success(toastBerhasil);
                        progressBar(response.titlechecklist.id, response
                            .titlechecklist
                            .percentage);
                        updateCheckboxes(response.checklist);

                        // Untuk Mengatur Icon Checklist //
                        $('#iconChecklist-' + response.titlechecklist.cards_id)
                            .removeClass(
                                'hidden');
                        $('#perhitunganChecklist-' + response.titlechecklist
                            .cards_id).html(
                            response.perChecklist + '/' + response
                            .jumlahChecklist);

                        if (response.perChecklist < response.jumlahChecklist) {
                            var tema_aplikasi = response.result_tema.tema_aplikasi;
                            var cardId = response.titlechecklist.cards_id;
                            var iconChecklist = $('#iconChecklist-' + cardId);
                            var iconChecklistCheck = $('#icon-checklist-' + cardId);

                            if (tema_aplikasi == 'Terang') {
                                iconChecklist.removeClass(
                                        'progress-checklist-100-light')
                                    .removeClass('progress-checklist-100-dark');
                                iconChecklist.addClass('progress-checklist-light')
                                    .removeClass(
                                        'progress-checklist-dark');
                                iconChecklistCheck.addClass(
                                        'icon-check-not-full-light')
                                    .removeClass('icon-check-not-full-dark');
                                iconChecklistCheck.removeClass(
                                        'icon-check-full-light')
                                    .removeClass('icon-check-full-dark');

                            } else if (tema_aplikasi == 'Gelap') {
                                iconChecklist.removeClass(
                                        'progress-checklist-100-dark')
                                    .removeClass('progress-checklist-100-light');
                                iconChecklist.addClass('progress-checklist-dark')
                                    .removeClass(
                                        'progress-checklist-light');
                                iconChecklistCheck.addClass(
                                        'icon-check-not-full-dark')
                                    .removeClass('icon-check-not-full-light');
                                iconChecklistCheck.removeClass(
                                        'icon-check-full-dark')
                                    .removeClass('icon-check-full-light');
                            }
                        } else if (response.perChecklist == response
                            .jumlahChecklist) {
                            var tema_aplikasi = response.result_tema.tema_aplikasi;
                            var cardId = response.titlechecklist.cards_id;
                            var iconChecklist = $('#iconChecklist-' + cardId);
                            var iconChecklistCheck = $('#icon-checklist-' + cardId);

                            if (tema_aplikasi == 'Terang') {
                                iconChecklist.addClass(
                                        'progress-checklist-100-light')
                                    .removeClass('progress-checklist-100-dark');
                                iconChecklist.addClass('progress-checklist-light')
                                    .removeClass(
                                        'progress-checklist-dark');
                                iconChecklistCheck.removeClass(
                                        'icon-check-not-full-light')
                                    .removeClass('icon-check-not-full-dark');
                                iconChecklistCheck.addClass('icon-check-full-light')
                                    .removeClass('icon-check-full-dark');

                            } else if (tema_aplikasi == 'Gelap') {
                                iconChecklist.addClass(
                                        'progress-checklist-100-dark')
                                    .removeClass('progress-checklist-100-light');
                                iconChecklist.addClass('progress-checklist-dark')
                                    .removeClass(
                                        'progress-checklist-light');
                                iconChecklistCheck.removeClass(
                                        'icon-check-not-full-dark')
                                    .removeClass('icon-check-not-full-light');
                                iconChecklistCheck.addClass('icon-check-full-dark')
                                    .removeClass(
                                        'icon-check-full-light');
                            }
                        }
                        // /Untuk Mengatur Icon Checklist //

                        // Setel ulang tanda
                        isSubmitting = false;
                    },
                    error: function(xhr) {
                        toastr.error('Gagal memperbarui checklist!');

                        // Setel ulang tanda
                        isSubmitting = false;
                    }
                });

                function updateCheckboxes(checklists) {
                    checklists.forEach(function(checklist) {
                        var checkbox = $('#' + checklist.id);
                        checkbox.prop('checked', checklist.is_active == 1);
                        var label = $('#labelCheckbox-' + checklist.id);
                        if (checklist.is_active == 1) {
                            label.addClass('strike-through');
                        } else {
                            label.removeClass('strike-through');
                        }
                    });
                }
            });
        });
    </script>

    {{-- NEW EDIT TITLE BIAR GA DI FOREACH 1 1 --}}
    <script>
        $(document).ready(function() {
            let isInputFocused = false;
            $(document).on('click', '.isian-title', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const title_id = $(this).data('id');
                const inputElement = $('#myFormTitleUpdate' + title_id).find('input[name="title"]');
                const val = $('#titleChecklistUpdate' + title_id).val();

                $('#saveButtonTitleUpdate' + title_id).removeClass('hidden');
                $('#cancelButtonTitleUpdate' + title_id).removeClass('hidden');
                $('#saveButtonTitleUpdate' + title_id).prop('disabled', false);
                $('#cancelButtonTitleUpdate' + title_id).prop('disabled', false);



                // Section Update Title
                $('#cancelButtonTitleUpdate' + title_id).on('click', function() {
                    $('#saveButtonTitleUpdate' + title_id).addClass('hidden');
                    $('#cancelButtonTitleUpdate' + title_id).addClass('hidden');
                    $('#titleChecklistUpdate' + title_id).val(val);
                });


                let isSubmitting = false;
                // Form update title
                $('#myFormTitleUpdate' + title_id).off('submit');
                $('#myFormTitleUpdate' + title_id).on('submit', function(e) {
                    e.preventDefault();
                    $('.spinner-border').removeClass('d-none');
                    $('#saveButtonTitleUpdate' + title_id).prop('disabled', true);
                    $('#cancelButtonTitleUpdate' + title_id).prop('disabled', true);
                    // Mencegah pengiriman ganda
                    if (isSubmitting) return;
                    isSubmitting = true;
                    // $('#myFormTitleUpdate' + title_id)[0].reset();
                    var formData = $(this).serialize();
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('updateTitle') }}",
                        data: formData,
                        success: function(response) {
                            $('.spinner-border').addClass('d-none');
                            $('#saveButtonTitleUpdate' + title_id).addClass('hidden');
                            $('#cancelButtonTitleUpdate' + title_id).addClass('hidden');
                            inputElement.val(response.name);
                            $('.isian-title').blur();
                            toastr.success('Berhasil memperbaharui judul!');
                            let isSubmitting = false;
                            localStorage.clear();
                        },
                        error: function(error) {
                            toastr.error('Gagal memperbaharui judul!');
                            let isSubmitting = false;
                        }
                    });
                    // return false;
                });
                // End Section Update Title
            });
            // Form delete title
            $(document).off('submit', '[id^="myFormTitleDelete"]');
            $(document).on('submit', '[id^="myFormTitleDelete"]', function(event) {
                event.preventDefault();

                // Mencegah pengiriman ganda
                if (isSubmitting) return;

                isSubmitting = true;
                var formData = $(this).serialize();
                var formId = $(this).attr('id');
                var titleId = formId.split('myFormTitleDelete')[1];
                $.ajax({
                    type: 'POST',
                    url: "{{ route('hapusTitle') }}",
                    data: formData,
                    success: function(response) {
                        localStorage.setItem('modal_id', response.card_id);
                        // Menghilangkan Title Checklist //
                        $('#' + formId).closest('.menu-checklist').remove();

                        // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                        var cardId = response.cardId;
                        var softDeletedTitle = response.softDeletedTitle;
                        var softDeletedChecklist = response.softDeletedChecklist;
                        var recoverTitleChecklist = $('#recover-title-checklist-' +
                            cardId);

                        if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                            recoverTitleChecklist.show();
                        } else {
                            recoverTitleChecklist.hide();
                        }

                        // Untuk Mengatur Icon Checklist //
                        if (response.jumlahChecklist === 0) {
                            $('#iconChecklist-' + response.cardId).addClass(
                                'hidden');
                        }
                        $('#perhitunganChecklist-' + response.cardId).html(response
                            .perChecklist + '/' + response.jumlahChecklist);

                        // if (response.perChecklist < response.jumlahChecklist) {
                        //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                        //     var cardId = response.cardId;
                        //     var iconChecklist = $('#iconChecklist-' + cardId);
                        //     var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        //     if (tema_aplikasi == 'Terang') {
                        //         iconChecklist.removeClass(
                        //                 'progress-checklist-100-light')
                        //             .removeClass('progress-checklist-100-dark');
                        //         iconChecklist.addClass('progress-checklist-light')
                        //             .removeClass(
                        //                 'progress-checklist-dark');
                        //         iconChecklistCheck.addClass(
                        //                 'icon-check-not-full-light')
                        //             .removeClass('icon-check-not-full-dark');
                        //         iconChecklistCheck.removeClass(
                        //                 'icon-check-full-light')
                        //             .removeClass('icon-check-full-dark');

                        //     } else if (tema_aplikasi == 'Gelap') {
                        //         iconChecklist.removeClass(
                        //                 'progress-checklist-100-dark')
                        //             .removeClass('progress-checklist-100-light');
                        //         iconChecklist.addClass('progress-checklist-dark')
                        //             .removeClass(
                        //                 'progress-checklist-light');
                        //         iconChecklistCheck.addClass(
                        //                 'icon-check-not-full-dark')
                        //             .removeClass('icon-check-not-full-light');
                        //         iconChecklistCheck.removeClass(
                        //                 'icon-check-full-dark')
                        //             .removeClass('icon-check-full-light');
                        //     }
                        // } else if (response.perChecklist == response
                        //     .jumlahChecklist) {
                        //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                        //     var cardId = response.cardId;
                        //     var iconChecklist = $('#iconChecklist-' + cardId);
                        //     var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        //     if (tema_aplikasi == 'Terang') {
                        //         iconChecklist.addClass(
                        //                 'progress-checklist-100-light')
                        //             .removeClass('progress-checklist-100-dark');
                        //         iconChecklist.addClass('progress-checklist-light')
                        //             .removeClass(
                        //                 'progress-checklist-dark');
                        //         iconChecklistCheck.removeClass(
                        //                 'icon-check-not-full-light')
                        //             .removeClass('icon-check-not-full-dark');
                        //         iconChecklistCheck.addClass('icon-check-full-light')
                        //             .removeClass('icon-check-full-dark');

                        //     } else if (tema_aplikasi == 'Gelap') {
                        //         iconChecklist.addClass(
                        //                 'progress-checklist-100-dark')
                        //             .removeClass('progress-checklist-100-light');
                        //         iconChecklist.addClass('progress-checklist-dark')
                        //             .removeClass(
                        //                 'progress-checklist-light');
                        //         iconChecklistCheck.removeClass(
                        //                 'icon-check-not-full-dark')
                        //             .removeClass('icon-check-not-full-light');
                        //         iconChecklistCheck.addClass('icon-check-full-dark')
                        //             .removeClass(
                        //                 'icon-check-full-light');
                        //     }
                        // }
                        // /Untuk Mengatur Icon Checklist //

                        toastr.success('Berhasil menghapus judul!');

                        // Show modal after create title
                        var modal_id = localStorage.getItem('modal_id');
                        $('#isianKartu' + modal_id).on('click', function() {
                            localStorage.clear();
                        });

                        // Setel ulang tanda
                        isSubmitting = false;
                    },
                    error: function(error) {
                        toastr.error('Gagal menghapus judul!');

                        // Setel ulang tanda
                        isSubmitting = false;
                    }
                });
            });
        });
    </script>


    {{-- NEW CREATE UPDATE DELETE CHECKLIST BIAR GA DI FOREACH 1 1 DAN MINUS 4K LINE SCRIPT ON ADDTITLE AND ANDTEMPLATE TITLE --}}
    <script>
        $(document).ready(function() {
            $(document).on('click', '.add-checklist', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const title_id = $(this).data('id');
                const percentage = $(this).data('persen');
                $(this).addClass('hidden');
                $('#checklist' + title_id).removeClass('hidden');
                // alert('test');
                $('#checklist' + title_id).focus();
                $('#saveButtonChecklist' + title_id).removeClass('hidden');
                $('#cancelButtonChecklist' + title_id).removeClass('hidden');
                $('#saveButtonChecklist' + title_id).prop('disabled', false);
                $('#cancelButtonChecklist' + title_id).prop('disabled', false);

                // Button cancel form checklist
                $('#cancelButtonChecklist' + title_id).on('click', function() {
                    $('.add-checklist').removeClass('hidden');
                    $('#checklist' + title_id).addClass('hidden');
                    $('#saveButtonChecklist' + title_id).addClass('hidden');
                    $('#cancelButtonChecklist' + title_id).addClass('hidden');
                    $('#myFormChecklist' + title_id)[0].reset();
                });

                isSubmitting = false;
                // Form Add Checklist
                $('#myFormChecklist' + title_id).on('submit', function(event) {
                    event.preventDefault();
                    $('.spinner-border').removeClass('d-none');
                    $('#saveButtonChecklist' + title_id).prop('disabled', true);
                    $('#cancelButtonChecklist' + title_id).prop('disabled', true);
                    // Mencegah pengiriman ganda
                    if (isSubmitting) return;
                    isSubmitting = true;
                    var formData = $(this).serialize();
                    // Periksa masukan yang kosong
                    var isEmpty = false;
                    $('#myFormChecklist' + title_id).find('input').each(function() {
                        if ($(this).val().trim() === '') {
                            isEmpty = true;
                        }
                    });
                    if (isEmpty) {
                        toastr.error('Bidang checklist tidak boleh kosong!');

                        // Setel ulang tanda
                        isSubmitting = false;
                        return;
                    }
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('addChecklist') }}",
                        data: formData,
                        success: function(response) {
                            $('.spinner-border').addClass('d-none');
                            $('.add-checklist').removeClass('hidden');
                            $('#checklist' + title_id).addClass('hidden');
                            $('#checklist' + title_id).val('');
                            $('#saveButtonChecklist' + title_id).addClass('hidden');
                            $('#cancelButtonChecklist' + title_id).addClass('hidden');

                            // Memunculkan checkbox ketika tambah data checklist
                            $('#checklistform-all-' + title_id).removeClass('hidden');

                            // Pengecekan pada checkbox
                            var checklistAllCheckbox = $('#checklistform-all-' +
                                title_id);
                            if (response.titlechecklist.percentage == 100) {
                                checklistAllCheckbox.prop('checked', true);
                            } else {
                                checklistAllCheckbox.prop('checked', false);
                            }

                            progressBar(response.titlechecklist.id, response
                                .titlechecklist
                                .percentage);
                            toastr.success('Berhasil membuat checklist!');
                            var newForm = `
                        <div id="section-checklist-${response.checklist.id}" class="input-checklist" data-id="${response.checklist.id}">
                            <form id="myFormChecklistUpdate${response.checklist.id}" method="POST" class="form-checklist">
                                @csrf
                                <input class="dynamicCheckbox" type="checkbox" id="${response.checklist.id}" name="${response.checklist.id}" ${response.checklist.is_active ? 'checked' : ''}>
                                <label onclick="mentionTags4('checkbox-${response.checklist.id}')" class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl ${response.checklist.is_active ? 'strike-through' : ''}" id="labelCheckbox-${response.checklist.id}" for="labelCheckbox-${response.checklist.id}">${response.checklist.name}</label>
                                <input type="hidden" id="checklist_id" name="checklist_id" value="${response.checklist.id}">
                                <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                <input  type="text" class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden" id="checkbox-${response.checklist.id}" name="checkbox-${response.checklist.id}" value="${response.checklist.name}" placeholder="Enter a checklist">
                                <div class="mention-tag" id="mention-tag-checkbox${response.checklist.id}"></div>

                                <div onclick="checklistUpdate(${response.checklist.id})" class="aksi-update-checklist2 gap-2 margin-bottom-0" id="checklist-${response.checklist.id}">
                                    <button type="button" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-${response.checklist.id}">

                                         <span class="spinner-border spinner-border-sm d-none mr-2 mb-1" role="status"
                                    aria-hidden="true"></span>
                                        Save</button>
                                    <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-${response.checklist.id}">Cancel</button>
                                </div>
                            </form>
                            <form id="myFormChecklistDelete${response.checklist.id}" method="POST">
                                @csrf
                                <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                <input type="hidden" id="title_checklists_id" name="title_checklists_id" value="${response.titlechecklist.id}">
                                <input type="hidden" id="id" name="id" value="${response.checklist.id}">
                                <div class="icon-hapus-checklist" id="hapus-checklist${response.checklist.id}">
                                    <button type="button" class="deletes" id="deleteButtonChecklist-${response.checklist.id}" style="border: none; background: none; padding: 0;">
                                        <div class="info-status6">
                                            <i class="fa fa-trash-o icon-trash"  @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif></i>
                                            <span class="text-status6"><b>Delete Checklist</b></span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>`;
                            $('#checklist-container-' + title_id).append(newForm);

                            // Untuk Mengatur Icon Checklist //
                            var iconChecklist = $('#iconChecklist-' + response
                                .titlechecklist
                                .cards_id);
                            var perhitunganChecklist = $('#perhitunganChecklist-' +
                                response
                                .titlechecklist.cards_id);

                            iconChecklist.removeClass('hidden');
                            if (perhitunganChecklist.length === 0) {
                                iconChecklist.append(`
                            <div class="info-status9">
                                <i id="icon-checklist-${response.titlechecklist.cards_id}" class="fa-regular fa-square-check icon-check-not-full-light
                                    ${response.perChecklist === response.jumlahChecklist ? 'icon-check-full-light' : ''}">
                                </i>
                                <span class="text-status9a"><b>Checklist items</b></span>
                                <span id="perhitunganChecklist-${response.titlechecklist.cards_id}" class="total">${response.perChecklist}/${response.jumlahChecklist}</span>
                            </div>
                        `);
                            } else {
                                perhitunganChecklist.html(response.perChecklist + '/' +
                                    response
                                    .jumlahChecklist);
                            }

                            if (response.perChecklist < response.jumlahChecklist) {
                                var tema_aplikasi = response.result_tema.tema_aplikasi;
                                var cardId = response.titlechecklist.cards_id;
                                var iconChecklist = $('#iconChecklist-' + cardId);
                                var iconChecklistCheck = $('#icon-checklist-' + cardId);

                                if (tema_aplikasi == 'Terang') {
                                    iconChecklist.removeClass(
                                            'progress-checklist-100-light')
                                        .removeClass('progress-checklist-100-dark');
                                    iconChecklist.addClass('progress-checklist-light')
                                        .removeClass(
                                            'progress-checklist-dark');
                                    iconChecklistCheck.addClass(
                                            'icon-check-not-full-light')
                                        .removeClass('icon-check-not-full-dark');
                                    iconChecklistCheck.removeClass(
                                            'icon-check-full-light')
                                        .removeClass('icon-check-full-dark');

                                } else if (tema_aplikasi == 'Gelap') {
                                    iconChecklist.removeClass(
                                            'progress-checklist-100-dark')
                                        .removeClass('progress-checklist-100-light');
                                    iconChecklist.addClass('progress-checklist-dark')
                                        .removeClass(
                                            'progress-checklist-light');
                                    iconChecklistCheck.addClass(
                                            'icon-check-not-full-dark')
                                        .removeClass('icon-check-not-full-light');
                                    iconChecklistCheck.removeClass(
                                            'icon-check-full-dark')
                                        .removeClass('icon-check-full-light');
                                }
                            } else if (response.perChecklist == response
                                .jumlahChecklist) {
                                var tema_aplikasi = response.result_tema.tema_aplikasi;
                                var cardId = response.titlechecklist.cards_id;
                                var iconChecklist = $('#iconChecklist-' + cardId);
                                var iconChecklistCheck = $('#icon-checklist-' + cardId);

                                if (tema_aplikasi == 'Terang') {
                                    iconChecklist.addClass(
                                            'progress-checklist-100-light')
                                        .removeClass('progress-checklist-100-dark');
                                    iconChecklist.addClass('progress-checklist-light')
                                        .removeClass(
                                            'progress-checklist-dark');
                                    iconChecklistCheck.removeClass(
                                            'icon-check-not-full-light')
                                        .removeClass('icon-check-not-full-dark');
                                    iconChecklistCheck.addClass('icon-check-full-light')
                                        .removeClass('icon-check-full-dark');

                                } else if (tema_aplikasi == 'Gelap') {
                                    iconChecklist.addClass(
                                            'progress-checklist-100-dark')
                                        .removeClass('progress-checklist-100-light');
                                    iconChecklist.addClass('progress-checklist-dark')
                                        .removeClass(
                                            'progress-checklist-light');
                                    iconChecklistCheck.removeClass(
                                            'icon-check-not-full-dark')
                                        .removeClass('icon-check-not-full-light');
                                    iconChecklistCheck.addClass('icon-check-full-dark')
                                        .removeClass(
                                            'icon-check-full-light');
                                }
                            }
                            // /Untuk Mengatur Icon Checklist //

                            // Setel ulang tanda
                            isSubmitting = false;
                            return false;
                        },
                        error: function(error) {
                            toastr.error('Gagal membuat checklist!');

                            // Setel ulang tanda
                            isSubmitting = false;
                            return false;
                        }
                    });
                });
            });

            // Checkbox form checklist
            $(document).off('change', '.dynamicCheckbox');
            $(document).on('change', '.dynamicCheckbox', function() {
                var checkbox = $(this);
                var isChecked = checkbox.is(':checked');
                var label = $('label[for="labelCheckbox-' + checkbox.attr('id') + '"]');
                if (isChecked) {
                    label.addClass('strike-through');
                    label.removeClass('hidden');
                    $('#checkbox-' + checkbox.attr('id')).addClass('hidden');
                    $('#saveButtonChecklistUpdate-' + checkbox.attr('id')).addClass('hidden');
                    $('#cancelButtonChecklistUpdate-' + checkbox.attr('id')).addClass('hidden');
                    formChecklist(checkbox.attr('id'));
                } else {
                    label.removeClass('strike-through');
                    label.removeClass('hidden');
                    $('#checkbox-' + checkbox.attr('id')).addClass('hidden');
                    $('#saveButtonChecklistUpdate-' + checkbox.attr('id')).addClass('hidden');
                    $('#cancelButtonChecklistUpdate-' + checkbox.attr('id')).addClass('hidden');
                    formChecklist(checkbox.attr('id'));
                }
            });
            // Label form checklist
            $(document).off('click', 'label[for]');

            $(document).on('click', 'label[for]', function(e) {
                e.preventDefault();
                var label = $(this).attr('for');
                var checkboxId = label.split('-');
                // alert(checkboxId[1]);
                // $('#checkbox-' + checkboxId[1]).focus();
                $('label[for="labelCheckbox-' + checkboxId[1] + '"]').addClass('hidden');
                $('#saveButtonChecklistUpdate-' + checkboxId[1]).removeClass('hidden');
                $('#cancelButtonChecklistUpdate-' + checkboxId[1]).removeClass('hidden');
                $('#saveButtonChecklistUpdate-' + checkboxId[1]).prop('disabled', false);
                $('#cancelButtonChecklistUpdate-' + checkboxId[1]).prop('disabled', false);
                $('#checklist-' + checkboxId[1]).css('margin-top', '5px');
                $('#checkbox-' + checkboxId[1]).removeClass('hidden');
                $('#checkbox-' + checkboxId[1]).focus();
            });
            // Button cancels form checklist
            $(document).off('click', '.cancels');
            $(document).on('click', '.cancels', function() {
                var id = $(this).attr('id').split('-');
                $('#checkbox-' + id[1]).addClass('hidden');
                $('#saveButtonChecklistUpdate-' + id[1]).addClass('hidden');
                $('#cancelButtonChecklistUpdate-' + id[1]).addClass('hidden');
                $('label[for="labelCheckbox-' + id[1] + '"]').removeClass('hidden');
                $('#checklist-' + id[1]).css('margin-top', '0px');
            });

            // Button saves form checklist
            $(document).off('click', '.saves');
            $(document).on('click', '.saves', function(event) {
                var id = $(this).attr('id').split('-');
                event.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $(this).prop('disabled', true);

                $('#cancelButtonChecklistUpdate-' + id[1]).prop('disabled', true);
                $('#checklist-' + id[1]).css('margin-top', '0px');
                // Mencegah pengiriman ganda
                if (isSubmitting) return;

                isSubmitting = true;
                var formData = $('#myFormChecklistUpdate' + id[1]).serialize();

                // Periksa masukan yang kosong
                var isEmpty = false;
                $('#myFormChecklistUpdate' + id).find('input').each(function() {
                    if ($(this).val().trim() === '') {
                        isEmpty = true;
                    }
                });
                if (isEmpty) {
                    toastr.error('Bidang checklist tidak boleh kosong!');

                    // Setel ulang tanda
                    isSubmitting = false;
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{ route('updateChecklist') }}",
                    data: formData,
                    success: function(response) {
                        $('.spinner-border').addClass('d-none');
                        $('label[for="labelCheckbox-' + response.checklist.id + '"]')
                            .removeClass('hidden');
                        $('label[for="labelCheckbox-' + response.checklist.id + '"]').html(
                            response.checklist.name);
                        $('#checkbox-' + response.checklist.id).addClass('hidden');
                        $('#saveButtonChecklistUpdate-' + response.checklist.id).addClass(
                            'hidden');
                        $('#cancelButtonChecklistUpdate-' + response.checklist.id).addClass(
                            'hidden');
                        toastr.success('Berhasil memperbaharui checklist!');

                        // Pengecekan pada checkbox
                        var checklistAllCheckbox = $('#checklistform-all-' + response
                            .titlechecklist.id);
                        if (response.titlechecklist.percentage == 100) {
                            checklistAllCheckbox.prop('checked', true);
                        } else {
                            checklistAllCheckbox.prop('checked', false);
                        }

                        // Untuk Mengatur Icon Checklist //
                        $('#iconChecklist-' + response.titlechecklist.cards_id).removeClass(
                            'hidden');
                        $('#perhitunganChecklist-' + response.titlechecklist.cards_id).html(
                            response.perChecklist + '/' + response.jumlahChecklist);

                        // if (response.perChecklist < response.jumlahChecklist) {
                        //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                        //     var cardId = response.titlechecklist.cards_id;
                        //     var iconChecklist = $('#iconChecklist-' + cardId);
                        //     var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        //     if (tema_aplikasi == 'Terang') {
                        //         iconChecklist.removeClass('progress-checklist-100-light')
                        //             .removeClass('progress-checklist-100-dark');
                        //         iconChecklist.addClass('progress-checklist-light').removeClass(
                        //             'progress-checklist-dark');
                        //         iconChecklistCheck.addClass('icon-check-not-full-light')
                        //             .removeClass('icon-check-not-full-dark');
                        //         iconChecklistCheck.removeClass('icon-check-full-light')
                        //             .removeClass('icon-check-full-dark');

                        //     } else if (tema_aplikasi == 'Gelap') {
                        //         iconChecklist.removeClass('progress-checklist-100-dark')
                        //             .removeClass('progress-checklist-100-light');
                        //         iconChecklist.addClass('progress-checklist-dark').removeClass(
                        //             'progress-checklist-light');
                        //         iconChecklistCheck.addClass('icon-check-not-full-dark')
                        //             .removeClass('icon-check-not-full-light');
                        //         iconChecklistCheck.removeClass('icon-check-full-dark')
                        //             .removeClass('icon-check-full-light');
                        //     }
                        // } else if (response.perChecklist == response.jumlahChecklist) {
                        //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                        //     var cardId = response.titlechecklist.cards_id;
                        //     var iconChecklist = $('#iconChecklist-' + cardId);
                        //     var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        //     if (tema_aplikasi == 'Terang') {
                        //         iconChecklist.addClass('progress-checklist-100-light')
                        //             .removeClass('progress-checklist-100-dark');
                        //         iconChecklist.addClass('progress-checklist-light').removeClass(
                        //             'progress-checklist-dark');
                        //         iconChecklistCheck.removeClass('icon-check-not-full-light')
                        //             .removeClass('icon-check-not-full-dark');
                        //         iconChecklistCheck.addClass('icon-check-full-light')
                        //             .removeClass('icon-check-full-dark');

                        //     } else if (tema_aplikasi == 'Gelap') {
                        //         iconChecklist.addClass('progress-checklist-100-dark')
                        //             .removeClass('progress-checklist-100-light');
                        //         iconChecklist.addClass('progress-checklist-dark').removeClass(
                        //             'progress-checklist-light');
                        //         iconChecklistCheck.removeClass('icon-check-not-full-dark')
                        //             .removeClass('icon-check-not-full-light');
                        //         iconChecklistCheck.addClass('icon-check-full-dark').removeClass(
                        //             'icon-check-full-light');
                        //     }
                        // }
                        // /Untuk Mengatur Icon Checklist //

                        localStorage.clear();

                        // Setel ulang tanda
                        isSubmitting = false;
                    },
                    error: function(error) {
                        toastr.error('Gagal memperbaharui checklist!');

                        // Setel ulang tanda
                        isSubmitting = false;
                    }
                });
            });
            // Form Update Checklist
            function formChecklist(id) {
                // Mencegah pengiriman ganda
                if (isSubmitting) return;

                isSubmitting = true;
                var formData = $('#myFormChecklistUpdate' + id).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('updateChecklist') }}",
                    data: formData,
                    success: function(response) {
                        $('label[for="labelCheckbox-' + response.checklist.id + '"]').removeClass(
                            'hidden');
                        $('label[for="labelCheckbox-' + response.checklist.id + '"]').html(response
                            .checklist.name);
                        $('#checkbox-' + response.checklist.id).addClass('hidden');
                        $('#saveButtonChecklistUpdate-' + response.checklist.id).addClass('hidden');
                        $('#cancelButtonChecklistUpdate-' + response.checklist.id).addClass('hidden');
                        toastr.success('Berhasil memperbaharui checklist!');

                        // Pengecekan pada checkbox
                        var checklistAllCheckbox = $('#checklistform-all-' + response.titlechecklist
                            .id);
                        if (response.titlechecklist.percentage == 100) {
                            checklistAllCheckbox.prop('checked', true);
                        } else {
                            checklistAllCheckbox.prop('checked', false);
                        }

                        // Untuk Mengatur Icon Checklist //
                        $('#iconChecklist-' + response.titlechecklist.cards_id).removeClass('hidden');
                        $('#perhitunganChecklist-' + response.titlechecklist.cards_id).html(response
                            .perChecklist + '/' + response.jumlahChecklist);

                        if (response.perChecklist < response.jumlahChecklist) {
                            var tema_aplikasi = response.result_tema.tema_aplikasi;
                            var cardId = response.titlechecklist.cards_id;
                            var iconChecklist = $('#iconChecklist-' + cardId);
                            var iconChecklistCheck = $('#icon-checklist-' + cardId);

                            if (tema_aplikasi == 'Terang') {
                                iconChecklist.removeClass('progress-checklist-100-light').removeClass(
                                    'progress-checklist-100-dark');
                                iconChecklist.addClass('progress-checklist-light').removeClass(
                                    'progress-checklist-dark');
                                iconChecklistCheck.addClass('icon-check-not-full-light').removeClass(
                                    'icon-check-not-full-dark');
                                iconChecklistCheck.removeClass('icon-check-full-light').removeClass(
                                    'icon-check-full-dark');

                            } else if (tema_aplikasi == 'Gelap') {
                                iconChecklist.removeClass('progress-checklist-100-dark').removeClass(
                                    'progress-checklist-100-light');
                                iconChecklist.addClass('progress-checklist-dark').removeClass(
                                    'progress-checklist-light');
                                iconChecklistCheck.addClass('icon-check-not-full-dark').removeClass(
                                    'icon-check-not-full-light');
                                iconChecklistCheck.removeClass('icon-check-full-dark').removeClass(
                                    'icon-check-full-light');
                            }
                        } else if (response.perChecklist == response.jumlahChecklist) {
                            var tema_aplikasi = response.result_tema.tema_aplikasi;
                            var cardId = response.titlechecklist.cards_id;
                            var iconChecklist = $('#iconChecklist-' + cardId);
                            var iconChecklistCheck = $('#icon-checklist-' + cardId);

                            if (tema_aplikasi == 'Terang') {
                                iconChecklist.addClass('progress-checklist-100-light').removeClass(
                                    'progress-checklist-100-dark');
                                iconChecklist.addClass('progress-checklist-light').removeClass(
                                    'progress-checklist-dark');
                                iconChecklistCheck.removeClass('icon-check-not-full-light').removeClass(
                                    'icon-check-not-full-dark');
                                iconChecklistCheck.addClass('icon-check-full-light').removeClass(
                                    'icon-check-full-dark');

                            } else if (tema_aplikasi == 'Gelap') {
                                iconChecklist.addClass('progress-checklist-100-dark').removeClass(
                                    'progress-checklist-100-light');
                                iconChecklist.addClass('progress-checklist-dark').removeClass(
                                    'progress-checklist-light');
                                iconChecklistCheck.removeClass('icon-check-not-full-dark').removeClass(
                                    'icon-check-not-full-light');
                                iconChecklistCheck.addClass('icon-check-full-dark').removeClass(
                                    'icon-check-full-light');
                            }
                        }

                        // /Untuk Mengatur Icon Checklist //

                        progressBar(response.titlechecklist.id, response.titlechecklist.percentage);
                        localStorage.clear();

                        // Setel ulang tanda
                        isSubmitting = false;
                    },
                    error: function(error) {
                        toastr.error('Gagal memperbaharui checklist!');

                        // Setel ulang tanda
                        isSubmitting = false;
                    }
                });
            }
            // Form delete Checklist
            $(document).off('click', '.deletes');
            $(document).on('click', '.deletes', function(event) {
                event.preventDefault();
                var id = $(this).attr('id').split('-');
                var formData = $('#myFormChecklistDelete' + id[1]).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('hapusChecklist') }}",
                    data: formData,
                    success: function(response) {
                        // Hapus Section Checklist
                        $('#section-checklist-' + id[1]).remove();

                        // Pengecekan pada checkbox
                        var checklistAllCheckbox = $('#checklistform-all-' + response
                            .titlechecklist.id);
                        if (response.titlechecklist.percentage === 0) {
                            checklistAllCheckbox.addClass('hidden');
                        }

                        // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                        var cardId = response.cardId;
                        var softDeletedTitle = response.softDeletedTitle;
                        var softDeletedChecklist = response.softDeletedChecklist;
                        var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                        if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                            recoverTitleChecklist.show();
                        } else {
                            recoverTitleChecklist.hide();
                        }

                        // Pengecekan pada checkbox
                        var checklistAllCheckbox = $('#checklistform-all-' + response
                            .titlechecklist.id);
                        if (response.titlechecklist.percentage == 100) {
                            checklistAllCheckbox.prop('checked', true);
                        } else {
                            checklistAllCheckbox.prop('checked', false);
                        }

                        // Untuk Mengatur Icon Checklist //
                        if (response.jumlahChecklist === 0) {
                            $('#iconChecklist-' + response.cardId).addClass('hidden');
                        }
                        $('#perhitunganChecklist-' + response.cardId).html(response
                            .perChecklist + '/' + response.jumlahChecklist);

                        if (response.perChecklist < response.jumlahChecklist) {
                            var tema_aplikasi = response.result_tema.tema_aplikasi;
                            var cardId = response.cardId;
                            var iconChecklist = $('#iconChecklist-' + cardId);
                            var iconChecklistCheck = $('#icon-checklist-' + cardId);

                            if (tema_aplikasi == 'Terang') {
                                iconChecklist.removeClass('progress-checklist-100-light')
                                    .removeClass('progress-checklist-100-dark');
                                iconChecklist.addClass('progress-checklist-light').removeClass(
                                    'progress-checklist-dark');
                                iconChecklistCheck.addClass('icon-check-not-full-light')
                                    .removeClass('icon-check-not-full-dark');
                                iconChecklistCheck.removeClass('icon-check-full-light')
                                    .removeClass('icon-check-full-dark');

                            } else if (tema_aplikasi == 'Gelap') {
                                iconChecklist.removeClass('progress-checklist-100-dark')
                                    .removeClass('progress-checklist-100-light');
                                iconChecklist.addClass('progress-checklist-dark').removeClass(
                                    'progress-checklist-light');
                                iconChecklistCheck.addClass('icon-check-not-full-dark')
                                    .removeClass('icon-check-not-full-light');
                                iconChecklistCheck.removeClass('icon-check-full-dark')
                                    .removeClass('icon-check-full-light');
                            }
                        } else if (response.perChecklist == response.jumlahChecklist) {
                            var tema_aplikasi = response.result_tema.tema_aplikasi;
                            var cardId = response.cardId;
                            var iconChecklist = $('#iconChecklist-' + cardId);
                            var iconChecklistCheck = $('#icon-checklist-' + cardId);

                            if (tema_aplikasi == 'Terang') {
                                iconChecklist.addClass('progress-checklist-100-light')
                                    .removeClass('progress-checklist-100-dark');
                                iconChecklist.addClass('progress-checklist-light').removeClass(
                                    'progress-checklist-dark');
                                iconChecklistCheck.removeClass('icon-check-not-full-light')
                                    .removeClass('icon-check-not-full-dark');
                                iconChecklistCheck.addClass('icon-check-full-light')
                                    .removeClass('icon-check-full-dark');

                            } else if (tema_aplikasi == 'Gelap') {
                                iconChecklist.addClass('progress-checklist-100-dark')
                                    .removeClass('progress-checklist-100-light');
                                iconChecklist.addClass('progress-checklist-dark').removeClass(
                                    'progress-checklist-light');
                                iconChecklistCheck.removeClass('icon-check-not-full-dark')
                                    .removeClass('icon-check-not-full-light');
                                iconChecklistCheck.addClass('icon-check-full-dark').removeClass(
                                    'icon-check-full-light');
                            }
                        }

                        // /Untuk Mengatur Icon Checklist //

                        progressBar(response.titlechecklist.id, response.titlechecklist
                            .percentage);
                        $('.checklistform-all').show();
                        toastr.success('Berhasil menghapus checklist!');
                    },
                    error: function(error) {
                        toastr.error('Gagal menghapus checklist!');
                    }
                });
            });
            // Tambahkan key Enter untuk validasi input dan menyimpan checklist
            $(document).ready(function() {
                $(document).on('keypress', function(e) {
                    if (e.which == 13) {
                        var activeElement = $(document.activeElement);
                        if (activeElement.is('input') || activeElement.is('textarea')) {
                            var form = activeElement.closest('form');

                            // Cegah pengiriman form secara default
                            e.preventDefault();

                            if (form.attr('id').startsWith('myFormChecklistUpdate')) {
                                form.find('.saves').click();
                            } else {
                                form.submit();
                            }
                        }
                    }
                });

                // Tandai untuk mencegah pengiriman berulang kali
                let isSubmitting = false;

                $('form').on('submit', function(event) {
                    const form = $(this);
                    if (form.attr('id').startsWith('myFormChecklistUpdate')) {
                        form.find('.saves').click();
                        toastr.success('Berhasil memperbaharui checklist!');
                        event.preventDefault();
                    }
                });

                $('.saves').on('click', function() {

                    // Setel ulang tanda
                    isSubmitting = false;
                });
            });

            // End Section Checklist

        });

        function checklistUpdate(id) {
            $(document).ready(function() {
                const dynamicCheckboxValue = document.getElementById(`checkbox-${id}`);
                const aksiUpdateChecklist = document.getElementById(`checklist-${id}`);
                const saveButton = document.getElementById(`saveButtonChecklistUpdate-${id}`);
                const cancelButton = document.getElementById(`cancelButtonChecklistUpdate-${id}`);


                // dynamicCheckboxValue.addEventListener('click', function() {
                //     aksiUpdateChecklist.style.marginBottom = '10px';
                //     aksiUpdateChecklist.style.marginTop = '5px';
                // });

                saveButton.addEventListener('click', function() {
                    aksiUpdateChecklist.style.marginBottom = '0';
                    aksiUpdateChecklist.style.marginTop = '0';
                });

                cancelButton.addEventListener('click', function() {
                    aksiUpdateChecklist.style.marginBottom = '0';
                    aksiUpdateChecklist.style.marginTop = '0';
                });
            });
        }
    </script>
