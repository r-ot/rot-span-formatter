(function () {
  const { registerFormatType, applyFormat, removeFormat } = wp.richText;
  const { RichTextToolbarButton } = wp.blockEditor;
  const { Fragment } = wp.element;
  const { __ } = wp.i18n;

  registerFormatType('rot/span-class', {
    title: __('Span mit Klasse', 'rot'),
    tagName: 'span',
    className: 'rot-span',
    attributes: {
      class: 'class',
    },
    edit({ isActive, value, onChange, activeAttributes }) {
      const currentClass = activeAttributes.class || '';

      const onToggle = () => {
        if (isActive) {
          onChange(removeFormat(value, 'rot/span-class'));
        } else {
          const enteredClass = window.prompt(__('CSS-Klasse für <span>', 'rot'), currentClass || 'highlight');
          if (enteredClass) {
            onChange(
              applyFormat(value, {
                type: 'rot/span-class',
                attributes: {
                  class: enteredClass,
                },
              })
            );
          }
        }
      };

      return wp.element.createElement(
        Fragment,
        null,
        wp.element.createElement(RichTextToolbarButton, {
          icon: 'editor-code',
          title: __('rot-span class', 'rot')  + (currentClass ? `: ${currentClass}` : ''),
          onClick: onToggle,
          isActive: isActive,
        })
      );
    },
  });
})();
