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
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

interface DeleteModalProps {
	isOpen: boolean;
	onDeletePress: () => void;
	onClose: () => void;
	title: string;
	isDeleting: boolean;
}

const DeleteModal: React.FC<DeleteModalProps> = (props) => {
	const { isOpen, onDeletePress, onClose, title, isDeleting } = props;

	return (
		<Modal isOpen={isOpen} onClose={onClose} isCentered>
			<ModalOverlay />
			<ModalContent>
				<ModalHeader>
					{__('Delete', 'masteriyo')} {title}
				</ModalHeader>
				<ModalCloseButton />
				<ModalBody>
					<Text fontSize="sm" color="gray.500">
						{__(
							"Are you sure want to delete. You won't be able to recover it back",
							'masteriyo'
						)}
					</Text>
				</ModalBody>
				<ModalFooter>
					<ButtonGroup>
						<Button
							colorScheme="red"
							onClick={onDeletePress}
							isLoading={isDeleting}>
							{__('Delete', 'masteriyo')}
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

export default DeleteModal;
