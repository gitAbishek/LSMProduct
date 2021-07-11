import {
	Button,
	Center,
	Divider,
	Icon,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
} from '@chakra-ui/react';
import { EditorContent, useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiBold,
	BiCode,
	BiCodeBlock,
	BiImageAdd,
	BiItalic,
	BiListUl,
	BiMinus,
	BiParagraph,
	BiStrikethrough,
	BiSubdirectoryLeft,
} from 'react-icons/bi';
import { ImQuotesLeft } from 'react-icons/im';
import { mergeDeep } from '../../utils/mergeDeep';

interface Props {
	name: `${string}`;
	control: any;
	defaultValue?: string;
}
const MenuBar = ({ editor }: any) => {
	const buttonStyles = (isActive?: boolean) => {
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

	const buttonCommonStyles = (isActive?: boolean) => {
		return mergeDeep(buttonStyles(isActive), {
			fontSize: 'sm',
			minW: 'auto',
			width: '30px',
			height: '30px',
			p: '1',
		});
	};

	if (!editor) {
		return null;
	}

	return (
		<Stack direction="row" spacing="1" align="center" justify="space-between">
			<Stack direction="row" spacing="1" align="center">
				<IconButton
					variant="unstyled"
					aria-label={__('Bold', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('bold'))}
					icon={<Icon as={BiBold} />}
					onClick={() => editor.chain().focus().toggleBold().run()}
				/>
				<IconButton
					variant="unstyled"
					aria-label={__('Italic', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('italic'))}
					icon={<Icon as={BiItalic} />}
					onClick={() => editor.chain().focus().toggleItalic().run()}
				/>

				<IconButton
					variant="unstyled"
					aria-label={__('Strike', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('strike'))}
					icon={<Icon as={BiStrikethrough} />}
					onClick={() => editor.chain().focus().toggleStrike().run()}
				/>
				<Center height="20px">
					<Divider orientation="vertical" />
				</Center>

				<IconButton
					variant="unstyled"
					aria-label={__('Code', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('code'))}
					icon={<Icon as={BiCode} />}
					onClick={() => editor.chain().focus().toggleCode().run()}
				/>

				<IconButton
					variant="unstyled"
					aria-label={__('Paragraph', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('paragraph'))}
					icon={<Icon as={BiParagraph} />}
					onClick={() => editor.chain().focus().setParagraph().run()}
				/>

				<Center height="20px">
					<Divider orientation="vertical" />
				</Center>
				<Menu>
					<MenuButton as={Button} variant="unstyled">
						Headings
					</MenuButton>
					<MenuList fontSize="xs">
						<MenuItem
							sx={buttonStyles(editor.isActive('heading', { level: 1 }))}
							onClick={() =>
								editor.chain().focus().toggleHeading({ level: 1 }).run()
							}>
							h1
						</MenuItem>
						<MenuItem
							sx={buttonStyles(editor.isActive('heading', { level: 2 }))}
							onClick={() =>
								editor.chain().focus().toggleHeading({ level: 2 }).run()
							}>
							h2
						</MenuItem>
						<MenuItem
							sx={buttonStyles(editor.isActive('heading', { level: 3 }))}
							onClick={() =>
								editor.chain().focus().toggleHeading({ level: 3 }).run()
							}>
							h3
						</MenuItem>
						<MenuItem
							sx={buttonStyles(editor.isActive('heading', { level: 4 }))}
							onClick={() =>
								editor.chain().focus().toggleHeading({ level: 4 }).run()
							}>
							h4
						</MenuItem>
						<MenuItem
							sx={buttonStyles(editor.isActive('heading', { level: 5 }))}
							onClick={() =>
								editor.chain().focus().toggleHeading({ level: 5 }).run()
							}>
							h5
						</MenuItem>
						<MenuItem
							sx={buttonStyles(editor.isActive('heading', { level: 6 }))}
							onClick={() =>
								editor.chain().focus().toggleHeading({ level: 6 }).run()
							}>
							h6
						</MenuItem>
					</MenuList>
				</Menu>

				<Center height="20px">
					<Divider orientation="vertical" />
				</Center>
				<IconButton
					variant="unstyled"
					aria-label={__('Bullet List', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('bulletList'))}
					icon={<Icon as={BiListUl} />}
					onClick={() => editor.chain().focus().toggleBulletList().run()}
				/>
				<IconButton
					variant="unstyled"
					aria-label={__('Ordered List', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('orderedList'))}
					icon={<Icon as={BiListUl} />}
					onClick={() => editor.chain().focus().toggleOrderedList().run()}
				/>

				<IconButton
					variant="unstyled"
					aria-label={__('Code Block', 'masteriyo')}
					sx={buttonCommonStyles(editor.isActive('codeBlock'))}
					icon={<Icon as={BiCodeBlock} />}
					onClick={() => editor.chain().focus().toggleCodeBlock().run()}
				/>

				<IconButton
					variant="unstyled"
					aria-label={__('Blockquote', 'masteriyo')}
					sx={buttonStyles(editor.isActive('blockquote'))}
					icon={<Icon as={ImQuotesLeft} />}
					onClick={() => editor.chain().focus().toggleBlockquote().run()}
				/>

				<IconButton
					variant="unstyled"
					aria-label={__('Horizontal Rule', 'masteriyo')}
					sx={buttonCommonStyles()}
					icon={<Icon as={BiMinus} />}
					onClick={() => editor.chain().focus().setHorizontalRule().run()}
				/>

				<IconButton
					variant="unstyled"
					aria-label={__('Hard Break', 'masteriyo')}
					sx={buttonCommonStyles()}
					icon={<Icon as={BiSubdirectoryLeft} />}
					onClick={() => editor.chain().focus().setHardBreak().run()}
				/>
			</Stack>
			<Stack direction="row" spacing="1" align="center">
				<IconButton
					variant="unstyled"
					aria-label={__('Hard Break', 'masteriyo')}
					sx={buttonCommonStyles()}
					icon={<Icon as={BiImageAdd} />}
					onClick={() => editor.chain().focus().setHardBreak().run()}
				/>
			</Stack>
		</Stack>
	);
};

const Editor: React.FC<Props> = (props) => {
	const editor = useEditor({
		extensions: [StarterKit],
		content: 'Your content',
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
