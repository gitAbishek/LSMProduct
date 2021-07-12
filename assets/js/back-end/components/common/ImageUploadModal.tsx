import {
	Button,
	ButtonGroup,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import ImageUpload from './ImageUpload';

interface Props {
	isOpen: boolean;
	onClose: any;
	onSucces?: any;
}
const ImageUploadModal: React.FC<Props> = (props) => {
	const { isOpen, onClose, onSucces } = props;
	const [imageUrl, setImageUrl] = useState(null);

	return (
		<Modal size="lg" isOpen={isOpen} onClose={onClose} isCentered>
			<ModalOverlay />
			<ModalContent>
				<ModalHeader
					bg="gray.800"
					borderTopRightRadius="xs"
					borderTopLeftRadius="xs"
					color="white"
					fontSize="sm">
					{__('Upload an Image', 'masteriyo')}
				</ModalHeader>
				<ModalCloseButton color="white" />
				<ModalBody py="6">
					<ImageUpload onUploadSuccess={setImageUrl} />
				</ModalBody>
				<ModalFooter bg="gray.50" borderTop="1px" borderColor="gray.100">
					<ButtonGroup>
						<Button
							colorScheme="blue"
							onClick={() => onSucces(imageUrl)}
							isDisabled={!imageUrl}>
							{__('Add Image', 'masteriyo')}
						</Button>
						<Button variant="outline" onClick={onClose}>
							{__('Cancel', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</ModalFooter>
			</ModalContent>
		</Modal>
	);
};

export default ImageUploadModal;
