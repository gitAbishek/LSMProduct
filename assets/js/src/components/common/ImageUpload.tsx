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
	Stack,
	Text,
	useToast,
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
	const toast = useToast();
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
		} else {
			toast({
				title: __('Please upload Image files', 'masteriyo'),
				description: __(
					'Media files jpeg, png are only supported',
					'masteriyo'
				),
				status: 'error',
				isClosable: true,
			});
		}
	};

	const onCrop = () => {
		const imageElement: any = cropperRef?.current;
		const cropper: any = imageElement?.cropper;
		console.log(cropper.getCroppedCanvas().toDataURL());
	};

	return (
		<>
			<Stack direction="column" spacing="6">
				<Box maxH="48" overflow="hidden">
					{preview && <Image src={preview} objectFit="cover" w="full" />}
				</Box>
				<ButtonGroup>
					<Button colorScheme="blue" onClick={() => setIsEditorOpen(true)}>
						Upload featured Image
					</Button>
				</ButtonGroup>
			</Stack>

			<Modal
				size="lg"
				isOpen={isEditorOpen}
				onClose={() => setIsEditorOpen(false)}>
				<ModalOverlay />
				<ModalContent>
					<ModalHeader
						bg="gray.800"
						borderTopRightRadius="xs"
						borderTopLeftRadius="xs"
						color="white"
						fontSize="sm">
						Edit Image
					</ModalHeader>
					<ModalCloseButton color="white" />
					<ModalBody>
						<Stack direction="column" spacing="8" p="8">
							<Box
								transition="ease-in-out"
								border="2px"
								borderStyle="dashed"
								borderColor="gray.300"
								bg={
									isDragAccept
										? 'green.50'
										: isDragReject
										? 'red.50'
										: 'gray.50'
								}
								position="relative"
								h="36"
								{...getRootProps()}>
								{preview && (
									<Image src={preview} objectFit="cover" maxH="full" />
								)}
								<input {...getInputProps()} multiple={false} />
								{!preview && (
									<Flex
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
												h="10"
												w="10"
												color={isDragActive ? 'blue.500' : 'gray.500'}
											/>
										</Box>
										<Text>{__('Upload an Image here', 'masteriyo')}</Text>
									</Flex>
								)}
							</Box>
						</Stack>
					</ModalBody>
					<ModalFooter bg="gray.50" borderTop="1px" borderColor="gray.100">
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
