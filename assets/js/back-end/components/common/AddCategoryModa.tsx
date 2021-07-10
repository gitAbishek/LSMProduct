import {
	Button,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useContext } from 'react';
import { CreateCatModal } from '../../context/CreateCatProvider';
import NameInput from '../../screens/course-categories/components/NameInput';
import SlugInput from '../../screens/course-categories/components/SlugInput';

const AddCategoryModa = () => {
	const { isCreateCatModalOpen, setIsCreateCatModalOpen } =
		useContext(CreateCatModal);
	return (
		<Modal
			isOpen={isCreateCatModalOpen}
			onClose={() => setIsCreateCatModalOpen(false)}>
			<ModalOverlay />
			<ModalContent>
				<form>
					<ModalHeader>{__('Add new category', 'masteriyo')}</ModalHeader>
					<ModalCloseButton />
					<ModalBody>
						<Stack direction="column" spacing="4">
							<NameInput />
							<SlugInput />
						</Stack>
					</ModalBody>

					<ModalFooter>
						<Button colorScheme="blue" type="submit" isFullWidth>
							{__('Add new Category', 'masteriyo')}
						</Button>
					</ModalFooter>
				</form>
			</ModalContent>
		</Modal>
	);
};

export default AddCategoryModa;
