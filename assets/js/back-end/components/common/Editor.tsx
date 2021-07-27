import { Box, Input, useOutsideClick } from '@chakra-ui/react';
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight';
import Dropcursor from '@tiptap/extension-dropcursor';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import { EditorContent, useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import lowlight from 'lowlight';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import EditorMenuBar from './EditorMenuBar';
interface Props {
	name: any;
	defaultValue?: string;
	hasImageUpload?: boolean;
	willReset?: boolean;
}

const Editor: React.FC<Props> = (props) => {
	const { name, defaultValue, hasImageUpload, willReset } = props;
	const { register, setValue } = useFormContext();
	const ref = React.useRef<any>();

	const editor = useEditor({
		extensions: [
			StarterKit,
			Image.configure({ inline: true }),
			Dropcursor,
			Placeholder,
			CodeBlockLowlight.configure({
				lowlight,
			}),
			TextAlign.configure({
				types: ['heading', 'paragraph'],
			}),
		],
		content: defaultValue,
	});

	useOutsideClick({
		ref: ref,
		handler: () => {
			setValue(name, editor?.getHTML());
			willReset && editor?.commands?.clearContent();
		},
	});

	return (
		<Box
			border="1px"
			borderColor="gray.200"
			shadow="input"
			rounded="sm"
			sx={{
				fontSize: 'sm',
				'.ProseMirror': {
					minH: '200px',
				},
				'.ProseMirror:focus': {
					outline: 'none',
				},
				'.ProseMirror p.is-editor-empty:first-of-type::before': {
					content: 'attr(data-placeholder)',
					float: 'left',
					color: 'gray.300',
					pointerEvents: 'none',
					height: '0',
				},
			}}>
			<Input type="hidden" {...register(name)} defaultValue={defaultValue} />
			<EditorMenuBar editor={editor} hasImageUpload={hasImageUpload} />
			<Box p="3" ref={ref}>
				<EditorContent editor={editor} />
			</Box>
		</Box>
	);
};

export default Editor;
