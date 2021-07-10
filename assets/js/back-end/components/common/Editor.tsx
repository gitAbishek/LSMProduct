import { Button, Icon, IconButton, Stack } from '@chakra-ui/react';
import { EditorContent, useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiBold, BiItalic, BiStrikethrough } from 'react-icons/bi';

interface Props {
	name: `${string}`;
	control: any;
	defaultValue?: string;
}
const MenuBar = ({ editor }: any) => {
	const buttonStyles = (isActive: boolean) => {
		if (isActive) {
			return {
				bg: 'blue.500',
				color: 'white',
			};
		} else {
			return {
				bg: 'transparent',
				color: 'gray.700',
			};
		}
	};

	if (!editor) {
		return null;
	}

	return (
		<Stack direction="row" spacing="0">
			<IconButton
				variant="unstyled"
				aria-label={__('Bold', 'masteriyo')}
				sx={buttonStyles(editor.isActive('bold'))}
				icon={<Icon as={BiBold} />}
				onClick={() => editor.chain().focus().toggleBold().run()}
			/>
			<IconButton
				variant="unstyled"
				aria-label={__('Bold', 'masteriyo')}
				sx={buttonStyles(editor.isActive('italic'))}
				icon={<Icon as={BiItalic} />}
				onClick={() => editor.chain().focus().toggleItalic().run()}
			/>

			<IconButton
				variant="unstyled"
				aria-label={__('Bold', 'masteriyo')}
				sx={buttonStyles(editor.isActive('strike'))}
				icon={<Icon as={BiStrikethrough} />}
				onClick={() => editor.chain().focus().toggleStrike().run()}
			/>

			<Button
				onClick={() => editor.chain().focus().toggleCode().run()}
				className={editor.isActive('code') ? 'is-active' : ''}>
				code
			</Button>
			<Button onClick={() => editor.chain().focus().unsetAllMarks().run()}>
				clear marks
			</Button>
			<Button onClick={() => editor.chain().focus().clearNodes().run()}>
				clear nodes
			</Button>
			<Button
				onClick={() => editor.chain().focus().setParagraph().run()}
				className={editor.isActive('paragraph') ? 'is-active' : ''}>
				paragraph
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleHeading({ level: 1 }).run()}
				className={editor.isActive('heading', { level: 1 }) ? 'is-active' : ''}>
				h1
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}
				className={editor.isActive('heading', { level: 2 }) ? 'is-active' : ''}>
				h2
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}
				className={editor.isActive('heading', { level: 3 }) ? 'is-active' : ''}>
				h3
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleHeading({ level: 4 }).run()}
				className={editor.isActive('heading', { level: 4 }) ? 'is-active' : ''}>
				h4
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleHeading({ level: 5 }).run()}
				className={editor.isActive('heading', { level: 5 }) ? 'is-active' : ''}>
				h5
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleHeading({ level: 6 }).run()}
				className={editor.isActive('heading', { level: 6 }) ? 'is-active' : ''}>
				h6
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleBulletList().run()}
				className={editor.isActive('bulletList') ? 'is-active' : ''}>
				bullet list
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleOrderedList().run()}
				className={editor.isActive('orderedList') ? 'is-active' : ''}>
				ordered list
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleCodeBlock().run()}
				className={editor.isActive('codeBlock') ? 'is-active' : ''}>
				code block
			</Button>
			<Button
				onClick={() => editor.chain().focus().toggleBlockquote().run()}
				className={editor.isActive('blockquote') ? 'is-active' : ''}>
				blockquote
			</Button>
			<Button onClick={() => editor.chain().focus().setHorizontalRule().run()}>
				horizontal rule
			</Button>
			<Button onClick={() => editor.chain().focus().setHardBreak().run()}>
				hard break
			</Button>
			<Button onClick={() => editor.chain().focus().undo().run()}>undo</Button>
			<Button onClick={() => editor.chain().focus().redo().run()}>redo</Button>
		</Stack>
	);
};

const Editor: React.FC<Props> = (props) => {
	const editor = useEditor({
		extensions: [StarterKit],
		content: '<p> Your content </p>',
	});

	const showJson = () => {
		console.log(editor?.getHTML());
	};
	return (
		<>
			<MenuBar editor={editor} />
			<EditorContent editor={editor} />
			<Button colorScheme="blue" onClick={showJson}>
				Show Code
			</Button>
		</>
	);
};

export default Editor;
