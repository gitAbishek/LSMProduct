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
	Stack,
} from '@chakra-ui/react';
import React from 'react';

interface Props {
	isOpen: boolean;
	onClose: any;
}
const ImageUploadModal: React.FC<Props> = (props) => {
	const { isOpen, onClose } = props;
	return (
		<Modal size="lg" isOpen={isOpen} onClose={onClose}>
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
					<Stack direction="column" spacing="8" p="8"></Stack>
				</ModalBody>
				<ModalFooter bg="gray.50" borderTop="1px" borderColor="gray.100">
					<ButtonGroup>
						<Button colorScheme="blue">Crop</Button>
						<Button variant="outline" onClick={onClose}>
							Cancel
						</Button>
					</ButtonGroup>
				</ModalFooter>
			</ModalContent>
		</Modal>
	);
};

export default ImageUploadModal;
