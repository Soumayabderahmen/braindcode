/**
 * Form Editors
 */

'use strict';

(function () {
  // Snow Theme
  // --------------------------------------------------------------------
  const snowEditor = new Quill('#snow-editor', {
    bounds: '#snow-editor',
    modules: {
      formula: true,
      toolbar: '#snow-toolbar'
    },
    theme: 'snow'
  });

  // Bubble Theme
  // --------------------------------------------------------------------
  const bubbleEditor = new Quill('#bubble-editor', {
    modules: {
      toolbar: '#bubble-toolbar'
    },
    theme: 'bubble'
  });

  // Full Toolbar
  // --------------------------------------------------------------------
  const fullToolbar = [
    [
      {
        font: []
      },
      {
        size: []
      }
    ],
    ['bold', 'italic', 'underline', 'strike'],
    [
      {
        color: []
      },
      {
        background: []
      }
    ],
    [
      {
        script: 'super'
      },
      {
        script: 'sub'
      }
    ],
    [
      {
        header: '1'
      },
      {
        header: '2'
      },
      'blockquote',
      'code-block'
    ],
    [
      {
        list: 'ordered'
      },
      {
        list: 'bullet'
      },
      {
        indent: '-1'
      },
      {
        indent: '+1'
      }
    ],
    [{ direction: 'rtl' }],
    ['link', 'image', 'video', 'formula'],
    ['clean']
  ];
  const fullEditor = new Quill('#full-editor', {
    bounds: '#full-editor',
    placeholder: 'Ecrire articte içi...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });
  const fullEditor2 = new Quill('#full-editor-2', {
    bounds: '#full-editor-2',
    placeholder: 'Ecrire un mail içi...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });
  const fullEditor3 = new Quill('#full-editor-3', {
    bounds: '#full-editor-3',
    placeholder: 'Ecrire içi...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });
  const fullEditor4 = new Quill('#full-editor-4', {
    bounds: '#full-editor-4',
    placeholder: 'Ecrire içi...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });
  const fullEditor5 = new Quill('#full-editor-5', {
    bounds: '#full-editor-5',
    placeholder: 'Ecrire içi...',
    modules: {
      formula: true,
      toolbar: fullToolbar
    },
    theme: 'snow'
  });
  window.fullEditor6 = new Quill('#full-editor-6', {
    bounds: '#full-editor-6',
    placeholder: '',
    modules: {
        formula: true,
        toolbar: fullToolbar
    },
    theme: 'snow'
});

})();
