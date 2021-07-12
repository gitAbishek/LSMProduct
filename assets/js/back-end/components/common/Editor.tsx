import {
	Box,
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
	useDisclosure,
} from '@chakra-ui/react';
import Dropcursor from '@tiptap/extension-dropcursor';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';
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
import ImageUploadModal from './ImageUploadModal';
interface Props {
	name: `${string}`;
	defaultValue?: string;
}

const MenuBar = ({ editor }: any) => {
	const { isOpen, onClose, onOpen } = useDisclosure();
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
			fontSize: '16px',
			minW: 'auto',
			width: '26px',
			height: '26px',
			p: '1',
		});
	};

	if (!editor) {
		return null;
	}

	const onImageUpload = (imageUrl: string) => {
		imageUrl && editor.chain().focus().setImage({ src: imageUrl }).run();
		onClose();
	};

	return (
		<Stack
			direction="row"
			spacing="1"
			align="center"
			justify="space-between"
			borderBottom="1px"
			borderColor="gray.100">
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
					onClick={onOpen}
				/>
				<ImageUploadModal
					isOpen={isOpen}
					onClose={onClose}
					onSucces={onImageUpload}
				/>
			</Stack>
		</Stack>
	);
};

const Editor: React.FC<Props> = () => {
	const editor = useEditor({
		extensions: [StarterKit, Image, Dropcursor, Placeholder],
	});

	return (
		<Box
			border="1px"
			borderColor="gray.100"
			sx={{
				fontSize: 'sm',
				'.ProseMirror': {
					minH: '200px',
				},
				'.ProseMirror p.is-editor-empty:first-child::before': {
					content: 'attr(data-placeholder)',
					float: 'left',
					color: 'gray.300',
					pointerEvents: 'none',
					height: '0',
				},
			}}>
			<MenuBar editor={editor} />
			<Box p="3">
				<EditorContent editor={editor} />
			</Box>
		</Box>
	);
};

export default Editor;
