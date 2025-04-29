/**
 * App Kanban
 */

'use strict';

(async function () {
  let boards;
  const kanbanSidebar = document.querySelector('.kanban-update-item-sidebar'),
    kanbanWrapper = document.querySelector('.kanban-wrapper'),
    commentEditor = document.querySelector('.comment-editor'),
    kanbanAddNewBoard = document.querySelector('.kanban-add-new-board'),
    kanbanAddNewInput = [].slice.call(document.querySelectorAll('.kanban-add-board-input')),
    kanbanAddBoardBtn = document.querySelector('.kanban-add-board-btn'),
    datePicker = document.querySelector('#due-date'),
    select2 = $('.select2'), // ! Using jquery vars due to select2 jQuery dependency
    assetsPath = document.querySelector('html').getAttribute('data-assets-path');

  // Init kanban Offcanvas
  const kanbanOffcanvas = new bootstrap.Offcanvas(kanbanSidebar);

  // Get kanban data
  const kanbanResponse = await fetch(assetsPath + 'json/kanban.json');
  if (!kanbanResponse.ok) {
    console.error('error', kanbanResponse);
  }
  boards = await kanbanResponse.json();

  // datepicker init
  if (datePicker) {
    datePicker.flatpickr({
      monthSelectorType: 'static',
      altInput: true,
      altFormat: 'j F, Y',
      dateFormat: 'Y-m-d'
    });
  }

  //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
  // select2
  if (select2.length) {
    function renderLabels(option) {
      if (!option.id) {
        return option.text;
      }
      var $badge = "<div class='badge " + $(option.element).data('color') + " rounded-pill'> " + option.text + '</div>';
      return $badge;
    }

    select2.each(function () {
      var $this = $(this);
      $this.wrap("<div class='position-relative'></div>").select2({
        placeholder: 'Select Label',
        dropdownParent: $this.parent(),
        templateResult: renderLabels,
        templateSelection: renderLabels,
        escapeMarkup: function (es) {
          return es;
        }
      });
    });
  }

  // Comment editor
  if (commentEditor) {
    new Quill(commentEditor, {
      modules: {
        toolbar: '.comment-toolbar'
      },
      placeholder: 'Write a Comment... ',
      theme: 'snow'
    });
  }

  // Render board dropdown
  function renderBoardDropdown() {
    return (
      "<div class='dropdown dropdown-2'>" +
      "<i class='dropdown-toggle ti ti-dots-vertical cursor-pointer' id='board-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>" +
      "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='board-dropdown'>" +
      "<a class='dropdown-item delete-board' href='javascript:void(0)'>Renommer</a>" +
      "<a class='dropdown-item' href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#addTaskModal'>Ajouter une tâche</a>" +
      "<a class='dropdown-item bg-danger text-white' href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#deleteModal'>Supprimer</a>" +
      "</div>" +
      "</div>"
    );

  }
  // Render item dropdown
  function renderDropdown() {
    return (
      "<div class='dropdown kanban-tasks-item-dropdown  dropdown-2'>" +
      "<i class='dropdown-toggle ti ti-dots-vertical' id='kanban-tasks-item-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></i>" +
      "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='kanban-tasks-item-dropdown'>" +
      "<a class='dropdown-item' href='javascript:void(0)' data-bs-toggle='modal' data-bs-target='#editTaskModal'>Modifier</a>" +
      "<a class='dropdown-item' href='javascript:void(0)'>Dupliquer</a>" +
      "<a class='dropdown-item delete-task bg-danger text-white' href='javascript:void(0)'>Supprimer</a>" +
      '</div>' +
      '</div>'
    );
  }
  // Render header
  function renderHeader(color, text) {
    return (
      "<div class='d-flex justify-content-between flex-wrap align-items-center mb-2 pb-1'>" +
      "<div class='item-badges'> " +
      "<div class='badge bg-label-" +
      color +
      "'> " +
      text +
      '</div>' +
      '</div>' +
      renderDropdown() +
      '</div>'
    );
  }
  // Render body
  function renderBody(description) {
    return (
      "<p class='kanban-desc'>" + description + "</p>"
    );
  }
  // Render avatar
  function renderAvatar(images, pullUp, size, margin, members) {
    var $transition = pullUp ? ' pull-up' : '',
      $size = size ? 'avatar-' + size + '' : '',
      member = members == undefined ? ' ' : members.split(',');

    return images == undefined
      ? ' '
      : images
        .split(',')
        .map(function (img, index, arr) {
          var $margin = margin && index !== arr.length - 1 ? ' me-' + margin + '' : '';

          return (
            "<div class='avatar " +
            $size +
            $margin +
            "'" +
            "data-bs-toggle='tooltip' data-bs-placement='top'" +
            "title='" +
            member[index] +
            "'" +
            '>' +
            "<img src='" +
            assetsPath +
            'img/avatars/' +
            img +
            "' alt='Avatar' class='rounded-circle " +
            $transition +
            "'>" +
            '</div>'
          );
        })
        .join(' ');
  }

  // Render footer
  function renderFooter(attachments, comments, assigned, members) {
    return (
      "<div class='d-flex justify-content-between align-items-center flex-wrap mt-2 pt-1'>" +
      "<div class='d-flex'>" +
      /* <span class='d-flex align-items-center me-2'><i class='ti ti-paperclip ti-xs me-1'></i>" +
      "<span class='attachments'>" +
      attachments +
      '</span>' +*/
      `</span> <span class='d-flex comments align-items-center ms-1'>
      <svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 15 15' fill='none'>
        <path d='M13.3333 4.83333C13.3333 4.11917 13.3333 3.63417 13.3017 3.25917C13.2725 2.89417 13.2183 2.70667 13.1517 2.57667L14.6367 1.82C14.8425 2.22417 14.925 2.655 14.9633 3.12333C15 3.58167 15 4.14667 15 4.83333H13.3333ZM13.3333 6.66667V4.83333H15V6.66667H13.3333ZM15 6.66667V10.8333H13.3333V6.66667H15ZM15 10.8333V13.2617H13.3333V10.8333H15ZM15 13.2617C15 14.3375 13.6983 14.8767 12.9375 14.1158L14.1158 12.9375C14.0517 12.8734 13.9701 12.8297 13.8811 12.812C13.7922 12.7943 13.7 12.8034 13.6162 12.8381C13.5325 12.8728 13.4609 12.9315 13.4105 13.0069C13.3602 13.0824 13.3333 13.171 13.3333 13.2617H15ZM12.9375 14.1158L10.4875 11.6667L11.6667 10.4883L14.1158 12.9375L12.9375 14.1158ZM4.83333 10H10.4883V11.6667H4.83333V10ZM2.57667 9.81833C2.70667 9.885 2.89333 9.93917 3.25917 9.96833C3.63417 9.99917 4.11917 10 4.83333 10V11.6667C4.14667 11.6667 3.5825 11.6667 3.12333 11.63C2.655 11.5917 2.22417 11.5092 1.82 11.3033L2.57667 9.81833ZM1.84834 9.09C2.00812 9.40359 2.26308 9.65855 2.57667 9.81833L1.82 11.3033C1.19282 10.9838 0.682908 10.4738 0.363336 9.84667L1.84834 9.09ZM1.66667 6.83333C1.66667 7.5475 1.66667 8.0325 1.69833 8.4075C1.7275 8.7725 1.78167 8.96 1.84834 9.09L0.363336 9.84667C0.157502 9.4425 0.0750017 9.01167 0.0366688 8.54333C-0.000831604 8.085 1.90735e-06 7.52 1.90735e-06 6.83333H1.66667ZM1.66667 4.83333V6.83333H1.90735e-06V4.83333H1.66667ZM1.84834 2.57667C1.78167 2.70667 1.7275 2.89333 1.69833 3.25917C1.66667 3.63417 1.66667 4.11917 1.66667 4.83333H1.90735e-06C1.90735e-06 4.14667 1.90735e-06 3.5825 0.0366688 3.12333C0.0750017 2.655 0.157502 2.22417 0.363336 1.82L1.84834 2.57667ZM2.57667 1.84833C2.26308 2.00812 2.00812 2.26308 1.84834 2.57667L0.363336 1.82C0.682908 1.19282 1.19282 0.682906 1.82 0.363333L2.57667 1.84833ZM4.83333 1.66667C4.11917 1.66667 3.63417 1.66667 3.25917 1.69833C2.89417 1.7275 2.70667 1.78167 2.57667 1.84833L1.82 0.363333C2.22417 0.1575 2.655 0.0750001 3.12333 0.0366668C3.58167 1.24176e-07 4.14667 0 4.83333 0V1.66667ZM10.1667 1.66667H4.83333V0H10.1667V1.66667ZM12.4233 1.84833C12.2933 1.78167 12.1067 1.7275 11.7408 1.69833C11.3658 1.66667 10.8808 1.66667 10.1667 1.66667V0C10.8533 0 11.4175 1.24176e-07 11.8767 0.0366668C12.345 0.0750001 12.7758 0.1575 13.18 0.363333L12.4233 1.84833ZM13.1517 2.57667C12.9919 2.26308 12.7369 2.00812 12.4233 1.84833L13.18 0.363333C13.8072 0.682906 14.3171 1.19282 14.6367 1.82L13.1517 2.57667ZM10.4883 11.6667V10C10.9303 10.0001 11.3542 10.1758 11.6667 10.4883L10.4883 11.6667Z' fill='#6B6B6B'/>
        <path d='M10.833 4.1665H4.16634M10.833 7.49984H6.66634' stroke='#6B6B6B' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/>
      </svg>
      `+
      ' <span> ' +
      comments +
      ' commentaires </span>' +
      '</span></div>' +
      "<div class='avatar-group d-flex align-items-center assigned-avatar'>" +
      renderAvatar(assigned, true, 'xs', null, members) +
      "<div class='extra-avatar d-flex align-items-center justify-content-center'>+2</div>" +
      "</div>"
      +
      '</div>'
    );
  }
  // Init kanban
  const kanban = new jKanban({
    element: '.kanban-wrapper',
    gutter: '15px',
    widthBoard: '250px',
    dragItems: true,
    boards: boards,
    dragBoards: true,
    addItemButton: true,
    /*buttonContent: '+ Add Item',
    itemAddOptions: {
      enabled: true, // add a button to board for easy item creation
      content: '+ Ajouter un nouveau', // text or html content of the board button
      class: 'kanban-title-button btn', // default class of the button
      footer: false // position the button on footer
    },*/
    click: function (el) {
      let element = el;
      let title = element.getAttribute('data-eid')
        ? element.querySelector('.kanban-text').textContent
        : element.textContent,
        date = element.getAttribute('data-due-date'),
        dateObj = new Date(),
        year = dateObj.getFullYear(),
        dateToUse = date
          ? date + ', ' + year
          : dateObj.getDate() + ' ' + dateObj.toLocaleString('en', { month: 'long' }) + ', ' + year,
        label = element.getAttribute('data-badge-text'),
        avatars = element.getAttribute('data-assigned');

      // Show kanban offcanvas
      kanbanOffcanvas.show();

      // To get data on sidebar
      kanbanSidebar.querySelector('#title').value = title;
      kanbanSidebar.querySelector('#due-date').nextSibling.value = dateToUse;

      // ! Using jQuery method to get sidebar due to select2 dependency
      $('.kanban-update-item-sidebar').find(select2).val(label).trigger('change');

      // Remove & Update assigned
      kanbanSidebar.querySelector('.assigned').innerHTML = '';
      kanbanSidebar
        .querySelector('.assigned')
        .insertAdjacentHTML(
          'afterbegin',
          renderAvatar(avatars, false, 'xs', '1', el.getAttribute('data-members')) +
          "<div class='avatar avatar-xs ms-1'>" +
          "<span class='avatar-initial rounded-circle bg-label-secondary'><i class='ti ti-plus ti-xs text-heading'></i></span>" +
          '</div>'
        );
    },

    buttonClick: function (el, boardId) {
      const addNew = document.createElement('form');
      addNew.setAttribute('class', 'new-item-form');
      addNew.innerHTML =
        '<div class="mb-3">' +
        '<textarea class="form-control add-new-item" rows="2" placeholder="Add Content" autofocus required></textarea>' +
        '</div>' +
        '<div class="mb-3">' +
        '<button type="submit" class="btn btn-primary btn-sm me-2">Add</button>' +
        '<button type="button" class="btn btn-label-secondary btn-sm cancel-add-item">Cancel</button>' +
        '</div>';
      kanban.addForm(boardId, addNew);

      addNew.addEventListener('submit', function (e) {
        e.preventDefault();
        const currentBoard = [].slice.call(
          document.querySelectorAll('.kanban-board[data-id=' + boardId + '] .kanban-item')
        );
        kanban.addElement(boardId, {
          title: "<span class='kanban-text'>" + e.target[0].value + '</span>',
          id: boardId + '-' + currentBoard.length + 1
        });

        // add dropdown in new boards
        const kanbanText = [].slice.call(
          document.querySelectorAll('.kanban-board[data-id=' + boardId + '] .kanban-text')
        );
        kanbanText.forEach(function (e) {
          e.insertAdjacentHTML('beforebegin', renderDropdown());
        });

        // prevent sidebar to open onclick dropdown buttons of new tasks
        const newTaskDropdown = [].slice.call(document.querySelectorAll('.kanban-item .kanban-tasks-item-dropdown'));
        if (newTaskDropdown) {
          newTaskDropdown.forEach(function (e) {
            e.addEventListener('click', function (el) {
              el.stopPropagation();
            });
          });
        }

        // delete tasks for new boards
        const deleteTask = [].slice.call(
          document.querySelectorAll('.kanban-board[data-id=' + boardId + '] .delete-task')
        );
        deleteTask.forEach(function (e) {
          e.addEventListener('click', function () {
            const id = this.closest('.kanban-item').getAttribute('data-eid');
            kanban.removeElement(id);
          });
        });
        addNew.remove();
      });

      // Remove form on clicking cancel button
      addNew.querySelector('.cancel-add-item').addEventListener('click', function (e) {
        addNew.remove();
      });
    }
  });

  // Kanban Wrapper scrollbar
  if (kanbanWrapper) {
    new PerfectScrollbar(kanbanWrapper);
  }

  const kanbanContainer = document.querySelector('.kanban-container'),
    kanbanTitleBoard = [].slice.call(document.querySelectorAll('.kanban-title-board')),
    kanbanItem = [].slice.call(document.querySelectorAll('.kanban-item'));

  // Render custom items
  if (kanbanItem) {
    kanbanItem.forEach(function (el) {
      const element = "<span class='kanban-text'>" + el.textContent + '</span>';
      const desc = "<p class='kanban-desc'>" + el.textContent + '</p>';
      let img = '';
      if (el.getAttribute('data-image') !== null) {
        img =
          "<img class='img-fluid rounded mb-2' src='" +
          assetsPath +
          'img/elements/' +
          el.getAttribute('data-image') +
          "'>";
      }
      el.textContent = '';
      if (el.getAttribute('data-badge') !== undefined && el.getAttribute('data-badge-text') !== undefined) {
        el.insertAdjacentHTML(
          'afterbegin',
          renderHeader(el.getAttribute('data-badge'), el.getAttribute('data-badge-text')) + img + element
        );
      }
      if (
        el.getAttribute('data-comments') !== undefined ||
        el.getAttribute('data-due-date') !== undefined ||
        el.getAttribute('data-assigned') !== undefined
      ) {
        el.insertAdjacentHTML(
          'beforeend',
          renderFooter(
            el.getAttribute('data-attachments'),
            el.getAttribute('data-comments'),
            el.getAttribute('data-assigned'),
            el.getAttribute('data-members')
          )
        );
      }
    });
  }

  // To initialize tooltips for rendered items
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // prevent sidebar to open onclick dropdown buttons of tasks
  const tasksItemDropdown = [].slice.call(document.querySelectorAll('.kanban-tasks-item-dropdown'));
  if (tasksItemDropdown) {
    tasksItemDropdown.forEach(function (e) {
      e.addEventListener('click', function (el) {
        el.stopPropagation();
      });
    });
  }

  // Toggle add new input and actions add-new-btn
  if (kanbanAddBoardBtn) {
    kanbanAddBoardBtn.addEventListener('click', () => {
      kanbanAddNewInput.forEach(el => {
        el.value = '';
        el.classList.toggle('d-none');
      });
    });
  }

  // Render add new inline with boards
  if (kanbanContainer) {
    kanbanContainer.appendChild(kanbanAddNewBoard);
  }

  // Makes kanban title editable for rendered boards
  if (kanbanTitleBoard) {
    kanbanTitleBoard.forEach(function (elem) {
      elem.addEventListener('mouseenter', function () {
        this.contentEditable = 'true';
      });

      // Appends delete icon with title
      elem.insertAdjacentHTML('afterend', renderBoardDropdown());
    });
  }

  // To delete Board for rendered boards
  const deleteBoards = [].slice.call(document.querySelectorAll('.delete-board'));
  if (deleteBoards) {
    deleteBoards.forEach(function (elem) {
      elem.addEventListener('click', function () {
        const id = this.closest('.kanban-board').getAttribute('data-id');
        kanban.removeBoard(id);
      });
    });
  }

  // Delete task for rendered boards
  const deleteTask = [].slice.call(document.querySelectorAll('.delete-task'));
  if (deleteTask) {
    deleteTask.forEach(function (e) {
      e.addEventListener('click', function () {
        const id = this.closest('.kanban-item').getAttribute('data-eid');
        kanban.removeElement(id);
      });
    });
  }

  // Cancel btn add new input
  const cancelAddNew = document.querySelector('.kanban-add-board-cancel-btn');
  if (cancelAddNew) {
    cancelAddNew.addEventListener('click', function () {
      kanbanAddNewInput.forEach(el => {
        el.classList.toggle('d-none');
      });
    });
  }

  // Add new board
  if (kanbanAddNewBoard) {
    kanbanAddNewBoard.addEventListener('submit', function (e) {
      e.preventDefault();
      const thisEle = this,
        value = thisEle.querySelector('.form-control').value,
        id = value.replace(/\s+/g, '-').toLowerCase();
      kanban.addBoards([
        {
          id: id,
          title: value
        }
      ]);

      // Adds delete board option to new board, delete new boards & updates data-order
      const kanbanBoardLastChild = document.querySelectorAll('.kanban-board:last-child')[0];
      if (kanbanBoardLastChild) {
        const header = kanbanBoardLastChild.querySelector('.kanban-title-board');
        header.insertAdjacentHTML('afterend', renderBoardDropdown());

        // To make newly added boards title editable
        kanbanBoardLastChild.querySelector('.kanban-title-board').addEventListener('mouseenter', function () {
          this.contentEditable = 'true';
        });
      }

      // Add delete event to delete newly added boards
      const deleteNewBoards = kanbanBoardLastChild.querySelector('.delete-board');
      if (deleteNewBoards) {
        deleteNewBoards.addEventListener('click', function () {
          const id = this.closest('.kanban-board').getAttribute('data-id');
          kanban.removeBoard(id);
        });
      }

      // Remove current append new add new form
      if (kanbanAddNewInput) {
        kanbanAddNewInput.forEach(el => {
          el.classList.add('d-none');
        });
      }

      // To place inline add new btn after clicking add btn
      if (kanbanContainer) {
        kanbanContainer.appendChild(kanbanAddNewBoard);
      }
    });
  }

  // Clear comment editor on close
  kanbanSidebar.addEventListener('hidden.bs.offcanvas', function () {
    kanbanSidebar.querySelector('.ql-editor').firstElementChild.innerHTML = '';
  });

  // Re-init tooltip when offcanvas opens(Bootstrap bug)
  if (kanbanSidebar) {
    kanbanSidebar.addEventListener('shown.bs.offcanvas', function () {
      const tooltipTriggerList = [].slice.call(kanbanSidebar.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  }
})();
