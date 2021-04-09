import 'cropperjs/dist/cropper.css';

import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	Icon,
	Image,
	Modal,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import Cropper from 'react-cropper';
import { useDropzone } from 'react-dropzone';
import { BiPlus } from 'react-icons/bi';

import ModalBody from './ModalBody';

interface Props {
	setFile: any;
}

const ImageUpload: React.FC<Props> = (props) => {
	const { setFile } = props;
	const [preview, setPreview] = useState<any>(null);
	const [isEditorOpen, setIsEditorOpen] = useState(false);
	const cropperRef = useRef<HTMLImageElement>(null);

	const {
		getRootProps,
		getInputProps,
		isDragAccept,
		isDragReject,
		isDragActive,
	} = useDropzone({
		accept: 'image/jpeg, image/png',
		onDrop: (acceptedFiles) => onDrop(acceptedFiles),
	});

	const onDrop = (acceptedFiles: any) => {
		if (acceptedFiles.length) {
			setFile(acceptedFiles[0]);
			setPreview(URL.createObjectURL(acceptedFiles[0]));
			setIsEditorOpen(true);
		}
	};

	const onCrop = () => {
		const imageElement: any = cropperRef?.current;
		const cropper: any = imageElement?.cropper;
		console.log(cropper.getCroppedCanvas().toDataURL());
	};

	return (
		<>
			<Box
				transition="ease-in-out"
				border="1px"
				borderStyle="dashed"
				borderColor="gray.300"
				bg={isDragAccept ? 'green.50' : isDragReject ? 'red.50' : 'gray.50'}
				position="relative"
				h="48"
				{...getRootProps()}>
				{preview && <Image src={preview} objectFit="cover" maxH="full" />}
				<input {...getInputProps()} multiple={false} />
				{!preview && (
					<Flex
						direction="column"
						align="center"
						justify="center"
						position="absolute"
						left="0"
						right="0"
						top="0"
						bottom="0">
						<Box>
							<Icon
								as={BiPlus}
								h="12"
								w="12"
								color={isDragActive ? 'blue.500' : 'gray.500'}
							/>
						</Box>
						<Text>{__('Upload an Image here', 'masteriyo')}</Text>
					</Flex>
				)}
			</Box>
			<Modal isOpen={isEditorOpen} onClose={() => setIsEditorOpen(false)}>
				<ModalOverlay />
				<ModalContent>
					<ModalHeader>Edit Image</ModalHeader>
					<ModalCloseButton />
					<ModalBody>
						<Cropper
							src={preview}
							initialAspectRatio={16 / 9}
							guides={false}
							crop={onCrop}
							ref={cropperRef}
						/>
					</ModalBody>
					<ModalFooter>
						<ButtonGroup>
							<Button colorScheme="blue" onClick={() => setIsEditorOpen(false)}>
								Crop
							</Button>
							<Button variant="outline" onClick={() => setIsEditorOpen(false)}>
								Cancel
							</Button>
						</ButtonGroup>
					</ModalFooter>
				</ModalContent>
			</Modal>
		</>
	);
};

export default ImageUpload;
