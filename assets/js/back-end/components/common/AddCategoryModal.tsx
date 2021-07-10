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
import { pickBy } from 'object-pickby';
import React, { useContext } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { CreateCatModal } from '../../context/CreateCatProvider';
import DescriptionInput from '../../screens/course-categories/components/DescriptionInput';
import NameInput from '../../screens/course-categories/components/NameInput';
import SlugInput from '../../screens/course-categories/components/SlugInput';

interface AddCatData {
	name: string;
	slug: string;
	description: string;
}

const AddCategoryModal = () => {
	const { isCreateCatModalOpen, setIsCreateCatModalOpen } =
		useContext(CreateCatModal);
	const methods = useForm();

	const onSubmit = (data: AddCatData) => {
		console.log(data);
		const newData = pickBy(data, (param) => param.length > 0);
	};

	return (
		<Modal
			isOpen={isCreateCatModalOpen}
			onClose={() => setIsCreateCatModalOpen(false)}
			size="xl">
			<ModalOverlay />
			<ModalContent>
				<FormProvider {...methods}>
					<form onSubmit={methods.handleSubmit(onSubmit)}>
						<ModalHeader>{__('Add new category', 'masteriyo')}</ModalHeader>
						<ModalCloseButton />
						<ModalBody>
							<Stack direction="column" spacing="4">
								<NameInput />
								<SlugInput />
								<DescriptionInput />
							</Stack>
						</ModalBody>

						<ModalFooter>
							<Button colorScheme="blue" type="submit" isFullWidth>
								{__('Add new Category', 'masteriyo')}
							</Button>
						</ModalFooter>
					</form>
				</FormProvider>
			</ModalContent>
		</Modal>
	);
};

export default AddCategoryModal;
