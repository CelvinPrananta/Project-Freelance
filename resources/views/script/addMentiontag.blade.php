<script>
    $(document).ready(function() {
        const users = [
            @foreach ($UserTeams as $result_team)
                {
                    username: "{{ $result_team->username }}",
                    name: "{{ $result_team->name }}",
                    email: "{{ $result_team->email }}",
                    avatar: "{{ URL::to('/assets/images/' . $result_team->avatar) }}"
                },
            @endforeach
        ];

        // Mention Tag untuk checklist
        window.mentionTags = function(inputId) {
            const inputTag = document.getElementById(inputId);
            const mentionTag = document.getElementById(
                `mention-tag-checklist${inputId.replace('checklist', '')}`);
            let selectedUsers = [];
            let isSubmitting = false;
            let mentionIndex = -1;
            // alert(inputId);
            inputTag.addEventListener('input', function(e) {
                const value = e.target.value;
                const atMatches = value.match(/@\w+/g);

                if (atMatches) {
                    const lastMatch = atMatches[atMatches.length - 1];
                    const query = lastMatch.substring(1).toLowerCase();

                    const lastMatchIndex = value.lastIndexOf(lastMatch);
                    const isAtEnd = lastMatchIndex + lastMatch.length === value.length;

                    if (isAtEnd) {
                        const filteredUsers = users.filter(user => user.username.toLowerCase()
                            .startsWith(query));
                        showMention(filteredUsers, value, inputTag, mentionTag);
                    } else {
                        mentionTag.style.display = 'none';
                    }
                } else {
                    mentionTag.style.display = 'none';
                }

                // Reset mentionIndex setiap input baru
                mentionIndex = -1;
            });

            function showMention(users, currentValue, inputTag, mentionTag) {
                mentionTag.innerHTML = '';
                if (users.length === 0) {
                    mentionTag.style.display = 'none';
                    return;
                }
                mentionTag.style.display = 'block';
                users.forEach(user => {
                    const item = document.createElement('div');
                    item.className = 'mention-tag-item';
                    item.style.display = 'flex';
                    item.style.alignItems = 'flex-end';

                    const avatarImg = document.createElement('img');
                    avatarImg.className = 'avatar-mention';
                    avatarImg.src = user.avatar;
                    avatarImg.loading = 'lazy';

                    const userInfo = document.createElement('div');
                    const username = document.createElement('div');
                    username.innerText = user.username;

                    const name = document.createElement('div');
                    name.innerText = user.name;
                    userInfo.appendChild(username);
                    userInfo.appendChild(name);

                    item.appendChild(avatarImg);
                    item.appendChild(userInfo);
                    item.addEventListener('click', function() {
                        const atMatches = currentValue.match(/@\w+/g);
                        const lastAtMatch = atMatches ? atMatches[atMatches.length - 1] :
                            '';

                        // Ganti mention yang belum lengkap dengan mention yang dipilih
                        const newValue = currentValue.slice(0, currentValue.lastIndexOf(
                            lastAtMatch)) + '@' + user.username.toLowerCase() + ' ';
                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih
                        inputTag.focus();

                        // Setel ke pengguna baru yang dipilih
                        selectedUsers.push(user);
                    });
                    mentionTag.appendChild(item);
                });
            }

            // Kalau tidak ada @ maka akan hidden container //
            document.addEventListener('click', function(event) {
                if (!mentionTag.contains(event.target) && event.target !== inputTag) {
                    mentionTag.style.display = 'none';
                }
            });

            // Kirimkan data mention ke notifikasi //
            const saveButtonId = `saveButtonChecklist${inputId.replace('checklist', '')}`;
            const saveButton = document.getElementById(saveButtonId);

            if (saveButton) {
                saveButton.addEventListener('click', function() {
                    if (!isSubmitting) {
                        sendData();
                    }
                });

                inputTag.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' && !isSubmitting) {
                        sendData();
                    }
                });

                function sendData() {
                    isSubmitting = true;

                    const name = inputTag.value;

                    const uniqueSelectedUsers = Array.from(new Set(selectedUsers.map(user => user
                            .username)))
                        .map(username => selectedUsers.find(user => user.username === username));

                    if (uniqueSelectedUsers.length > 0) {
                        const promises = uniqueSelectedUsers.map(user => {
                            return fetch('/mention-tag-checklist', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    username: user.username,
                                    name: name
                                })
                            });
                        });

                        Promise.all(promises)
                            .then(responses => {
                                const allSuccessful = responses.every(response => response.ok);
                                if (allSuccessful) {
                                    toastr.success('Berhasil mengirimkan mention tag!');
                                } else {
                                    toastr.error('Gagal mengirimkan mention tag!');
                                }

                                // Reset pengguna yang dipilih setelah mengirim data
                                selectedUsers = [];
                                isSubmitting = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                selectedUsers = [];
                                isSubmitting = false;
                            });
                    } else {
                        isSubmitting = false;
                    }
                }
            }
            // /Kirimkan data mention ke notifikasi //
        }
        // Mention Tag untuk desc
        window.mentionTags2 = function(inputId) {
            const inputTag = document.getElementById(inputId);
            const mentionTag = document.getElementById(
                `mention-tag-keterangan${inputId.replace('keterangan', '')}`);
            let selectedUsers = [];

            inputTag.addEventListener('input', function(e) {
                const value = e.target.value;
                const atPosition = value.lastIndexOf('@');
                if (atPosition !== -1) {
                    const query = value.substring(atPosition + 1).toLowerCase();
                    const filteredUsers = users.filter(user => user.username.toLowerCase()
                        .startsWith(query));
                    showMention2(filteredUsers, atPosition, value, inputTag, mentionTag);
                } else {
                    mentionTag.style.display = 'none';
                }
            });

            function showMention2(users, atPosition, currentValue, inputTag, mentionTag) {
                mentionTag.innerHTML = '';
                if (users.length === 0) {
                    mentionTag.style.display = 'none';
                    return;
                }
                mentionTag.style.display = 'block';
                users.forEach(user => {
                    // Untuk div avatar, username, name/email //
                    const item = document.createElement('div');
                    item.className = 'mention-tag-keterangan-item';
                    item.style.display = 'flex';
                    item.style.alignItems = 'flex-end';
                    // /Untuk div avatar, username, name/email //

                    // Untuk img avatar //
                    const avatarImg = document.createElement('img');
                    avatarImg.className = 'avatar-mention';
                    avatarImg.src = user.avatar;
                    avatarImg.loading = 'lazy';
                    // /Untuk img avatar //

                    // Untuk inputan yang dikeluarkan //
                    const userInfo = document.createElement('div');
                    const username = document.createElement('div');
                    username.innerText = user.username;
                    // /Untuk inputan yang dikeluarkan //

                    // Untuk email //
                    // const email = document.createElement('div');
                    // email.innerText = user.email;
                    // userInfo.appendChild(username);
                    // userInfo.appendChild(email);
                    // /Untuk email //

                    // Untuk nama lengkap //
                    const name = document.createElement('div');
                    name.innerText = user.name;
                    userInfo.appendChild(username);
                    userInfo.appendChild(name);
                    // /Untuk nama lengkap //

                    item.appendChild(avatarImg);
                    item.appendChild(userInfo);
                    item.addEventListener('click', function() {

                        // Untuk inputan yang dikeluarkan //
                        const newValue = currentValue.substring(0, atPosition + 1) + user
                            .username.toLowerCase() + ' ';
                        // /Untuk inputan yang dikeluarkan //

                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih //
                        inputTag.focus();
                        selectedUsers.push(user);
                        // /Kembali fokus ke input setelah memilih //

                    });
                    mentionTag.appendChild(item);
                });
            }

            // Kalau tidak ada @ maka akan hidden container //
            document.addEventListener('click', function(event) {
                if (!mentionTag.contains(event.target) && event.target !== inputTag) {
                    mentionTag.style.display = 'none';
                }
            });
            // /Kalau tidak ada @ maka akan hidden container //

            // Kirimkan data mention ke notifikasi //
            const saveButtonId = `saveButton${inputId.replace('keterangan', '')}`;
            const saveButton = document.getElementById(saveButtonId);

            saveButton.addEventListener('click', function() {
                const descriptionTextarea = document.getElementById(inputId);
                const description = descriptionTextarea.value;

                if (selectedUsers.length > 0) {
                    const promises = selectedUsers.map(user => {
                        return fetch('/mention-tag-description', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                username: user.username,
                                keterangan: description
                            })
                        });
                    });

                    Promise.all(promises)
                        .then(responses => {
                            const allSuccessful = responses.every(response => response.ok);
                            if (allSuccessful) {
                                toastr.success('Berhasil mengirimkan mention tag!');
                            } else {
                                toastr.error('Gagal mengirimkan mention tag!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                };
            });
            // /Kirimkan data mention ke notifikasi //

        }

        // // Mention Tag Untuk comment
        // window.mentionTags3 = function(inputId) {
        //     const inputTag = document.getElementById(inputId);
        //     const mentionTag = document.getElementById(
        //         `mention-tag-comment${inputId.replace('contentarea', '')}`);
        //     let selectedUsers = [];

        //     inputTag.addEventListener('input', function(e) {
        //         const value = e.target.value;
        //         const atPosition = value.lastIndexOf('@');
        //         if (atPosition !== -1) {
        //             const query = value.substring(atPosition + 1).toLowerCase();
        //             const filteredUsers = users.filter(user => user.username.toLowerCase()
        //                 .startsWith(query));
        //             showMention3(filteredUsers, atPosition, value, inputTag, mentionTag);
        //         } else {
        //             mentionTag.style.display = 'none';
        //         }
        //     });

        //     function showMention3(users, atPosition, currentValue, inputTag, mentionTag) {
        //         mentionTag.innerHTML = '';
        //         if (users.length === 0) {
        //             mentionTag.style.display = 'none';
        //             return;
        //         }
        //         mentionTag.style.display = 'block';
        //         users.forEach(user => {
        //             // Untuk div avatar, username, name/email //
        //             const item = document.createElement('div');
        //             item.className = 'mention-tag-comment-item';
        //             item.style.display = 'flex';
        //             item.style.alignItems = 'flex-end';
        //             // /Untuk div avatar, username, name/email //

        //             // Untuk img avatar //
        //             const avatarImg = document.createElement('img');
        //             avatarImg.className = 'avatar-mention';
        //             avatarImg.src = user.avatar;
        //             avatarImg.loading = 'lazy';
        //             // /Untuk img avatar //

        //             // Untuk inputan yang dikeluarkan //
        //             const userInfo = document.createElement('div');
        //             const username = document.createElement('div');
        //             username.innerText = user.username;
        //             // /Untuk inputan yang dikeluarkan //

        //             // Untuk email //
        //             // const email = document.createElement('div');
        //             // email.innerText = user.email;
        //             // userInfo.appendChild(username);
        //             // userInfo.appendChild(email);
        //             // /Untuk email //

        //             // Untuk nama lengkap //
        //             const name = document.createElement('div');
        //             name.innerText = user.name;
        //             userInfo.appendChild(username);
        //             userInfo.appendChild(name);
        //             // /Untuk nama lengkap //

        //             item.appendChild(avatarImg);
        //             item.appendChild(userInfo);
        //             item.addEventListener('click', function() {

        //                 // Untuk inputan yang dikeluarkan //
        //                 const newValue = currentValue.substring(0, atPosition + 1) + user
        //                     .username.toLowerCase() + ' ';
        //                 // /Untuk inputan yang dikeluarkan //

        //                 inputTag.value = newValue;
        //                 mentionTag.style.display = 'none';

        //                 // Kembali fokus ke input setelah memilih //
        //                 inputTag.focus();
        //                 selectedUsers.push(user);
        //                 // /Kembali fokus ke input setelah memilih //

        //             });
        //             mentionTag.appendChild(item);
        //         });
        //     }

        //     // Kalau tidak ada @ maka akan hidden container //
        //     document.addEventListener('click', function(event) {
        //         if (!mentionTag.contains(event.target) && event.target !== inputTag) {
        //             mentionTag.style.display = 'none';
        //         }
        //     });
        //     // /Kalau tidak ada @ maka akan hidden container //

        //     // Kirimkan data mention ke notifikasi //
        //     const saveButtonId = `simpanButton${inputId.replace('contentarea', '')}`;
        //     const saveButton = document.getElementById(saveButtonId);

        //     saveButton.addEventListener('click', function() {
        //         const contentTextarea = document.getElementById(inputId);
        //         const content = contentTextarea.value;

        //         if (selectedUsers.length > 0) {
        //             const promises = selectedUsers.map(user => {
        //                 return fetch('/mention-tag-comment', {
        //                     method: 'POST',
        //                     headers: {
        //                         'Content-Type': 'application/json',
        //                         'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                     },
        //                     body: JSON.stringify({
        //                         username: user.username,
        //                         content: content
        //                     })
        //                 });
        //             });

        //             Promise.all(promises)
        //                 .then(responses => {
        //                     const allSuccessful = responses.every(response => response.ok);
        //                     if (allSuccessful) {
        //                         toastr.success('Berhasil mengirimkan mention tag!');
        //                         selectedUsers = [];
        //                     } else {
        //                         toastr.error('Gagal mengirimkan mention tag!');
        //                     }
        //                 })
        //                 .catch(error => {
        //                     console.error('Error:', error);
        //                 });
        //         };
        //     });
        //     // /Kirimkan data mention ke notifikasi //

        // }

        // Mention Tag Untuk edit
        window.mentionTags4 = function(inputId) {
            const inputTag = document.getElementById(inputId);
            const mentionTag = document.getElementById(
                `mention-tag-checkbox${inputId.replace('checkbox-', '')}`);
            let selectedUsers = [];
            let isSubmitting = false;
            let mentionIndex = -1;

            inputTag.addEventListener('input', function(e) {
                const value = e.target.value;
                const atMatches = value.match(/@\w+/g);

                if (atMatches) {
                    const lastMatch = atMatches[atMatches.length - 1];
                    const query = lastMatch.substring(1).toLowerCase();

                    const lastMatchIndex = value.lastIndexOf(lastMatch);
                    const isAtEnd = lastMatchIndex + lastMatch.length === value.length;

                    if (isAtEnd) {
                        const filteredUsers = users.filter(user => user.username.toLowerCase()
                            .startsWith(query));
                        showMention4(filteredUsers, value, inputTag, mentionTag);
                    } else {
                        mentionTag.style.display = 'none';
                    }
                } else {
                    mentionTag.style.display = 'none';
                }

                // Reset mentionIndex setiap input baru
                mentionIndex = -1;
            });

            //         inputTag.addEventListener('keydown', function(event) {
            //             const mentionItems = mentionTag.querySelectorAll('.mention-tag-item');

            //             if (event.key === 'Tab' && mentionItems.length > 0) {
            //                 event.preventDefault();

            //                 // Navigasi antar item
            //                 mentionIndex = (mentionIndex + 1) % mentionItems.length;

            //                 // Tambahkan gaya yang menyorot item yang dipilih
            //                 mentionItems.forEach((item, index) => {
            //                     if (index === mentionIndex) {
            //                         item.classList.add('highlighted');
            //                         item.scrollIntoView({
            //                             behavior: 'smooth',
            //                             block: 'nearest',
            //                             inline: 'nearest'
            //                         });
            //                     } else {
            //                         item.classList.remove('highlighted');
            //                     }
            //                 });
            //             }

            //             if ((event.key === 'Enter' || event.key === ' ') && mentionItems.length > 0 &&
            //                 mentionIndex >= 0) {
            //                 event.preventDefault();
            //                 // Pilih item yang disorot
            //                 mentionItems[mentionIndex].click();
            //             }
            //         });

            //         // Tambahkan gaya CSS untuk menyorot item yang dipilih
            //         const style = document.createElement('style');
            //         style.innerHTML = `
            //     .mention-tag-item.highlighted {
            //         background-color: #ddd;
            //     }
            // `;
            // document.head.appendChild(style);

            function showMention4(users, currentValue, inputTag, mentionTag) {
                mentionTag.innerHTML = '';
                if (users.length === 0) {
                    mentionTag.style.display = 'none';
                    return;
                }
                mentionTag.style.display = 'block';
                users.forEach(user => {
                    const item = document.createElement('div');
                    item.className = 'mention-tag-item';
                    item.style.display = 'flex';
                    item.style.alignItems = 'flex-end';

                    const avatarImg = document.createElement('img');
                    avatarImg.className = 'avatar-mention';
                    avatarImg.src = user.avatar;
                    avatarImg.loading = 'lazy';

                    const userInfo = document.createElement('div');
                    const username = document.createElement('div');
                    username.innerText = user.username;

                    const name = document.createElement('div');
                    name.innerText = user.name;
                    userInfo.appendChild(username);
                    userInfo.appendChild(name);

                    item.appendChild(avatarImg);
                    item.appendChild(userInfo);
                    item.addEventListener('click', function() {
                        const atMatches = currentValue.match(/@\w+/g);
                        const lastAtMatch = atMatches ? atMatches[atMatches.length - 1] :
                            '';

                        // Ganti mention yang belum lengkap dengan mention yang dipilih
                        const newValue = currentValue.slice(0, currentValue.lastIndexOf(
                            lastAtMatch)) + '@' + user.username.toLowerCase() + ' ';
                        inputTag.value = newValue;
                        mentionTag.style.display = 'none';

                        // Kembali fokus ke input setelah memilih
                        inputTag.focus();

                        // Setel ke pengguna baru yang dipilih
                        selectedUsers.push(user);
                    });
                    mentionTag.appendChild(item);
                });
            }

            document.addEventListener('click', function(event) {
                if (!mentionTag.contains(event.target) && event.target !== inputTag) {
                    mentionTag.style.display = 'none';
                }
            });

            const saveButtonId = `saveButtonChecklistUpdate${inputId.replace('checkbox', '')}`;
            const saveButton = document.getElementById(saveButtonId);

            if (saveButton) {
                saveButton.addEventListener('click', function() {
                    if (!isSubmitting) {
                        sendData();
                    }
                });

                inputTag.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter' && !isSubmitting) {
                        sendData();
                    }
                });

                function sendData() {
                    isSubmitting = true;

                    const name = inputTag.value;

                    const uniqueSelectedUsers = Array.from(new Set(selectedUsers.map(user => user
                            .username)))
                        .map(username => selectedUsers.find(user => user.username === username));

                    if (uniqueSelectedUsers.length > 0) {
                        const promises = uniqueSelectedUsers.map(user => {
                            return fetch('/mention-tag-checklist', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    username: user.username,
                                    name: name
                                })
                            });
                        });

                        Promise.all(promises)
                            .then(responses => {
                                const allSuccessful = responses.every(response => response.ok);
                                if (allSuccessful) {
                                    toastr.success('Berhasil mengirimkan mention tag!');
                                } else {
                                    toastr.error('Gagal mengirimkan mention tag!');
                                }

                                // Reset pengguna yang dipilih setelah mengirim data
                                selectedUsers = [];
                                isSubmitting = false;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                selectedUsers = [];
                                isSubmitting = false;
                            });
                    } else {
                        isSubmitting = false;
                    }
                }
            }
        }



    });
</script>
