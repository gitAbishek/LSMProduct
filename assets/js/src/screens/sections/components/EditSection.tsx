import {
	Button,
	ButtonGroup,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Stack,
	Textarea,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useForm } from 'react-hook-form';
import { useMutation, useQueryClient } from 'react-query';

import urls from '../../../constants/urls';
import API from '../../../utils/api';

export interface EditSectionProps {
	id: number;
	name: string;
	description?: string;
	onSave: () => void;
	onCancel: () => void;
}

type SectionInputs = {
	name?: string;
	description?: string;
};

const EditSection: React.FC<EditSectionProps> = (props) => {
	const { id, name, description, onSave, onCancel } = props;
	const {
		register,
		handleSubmit,
		formState: { errors },
	} = useForm<SectionInputs>();
	const queryClient = useQueryClient();
	const toast = useToast();
	const sectionAPI = new API(urls.sections);

	const updateMutation = useMutation((data: any) => updateSection(id, data), {
		onSuccess: (data: any) => {
			toast({
				title: __('Updated Successfully', 'masteriyo'),
				description: data.name + __(' is updated succesffuly', 'masteriyo'),
				status: 'success',
				isClosable: true,
			});
			queryClient.invalidateQueries('builderSections');
			onSave();
		},
	});

	const onUpdate = (data: any) => {
		updateMutation.mutate(data);
	};

	return (
		<form onSubmit={handleSubmit(onUpdate)}>
			<Stack direction="column" spacing="8">
				<FormControl isInvalid={!!errors?.name}>
					<FormLabel htmlFor="">{__('Section Name', 'masteriyo')}</FormLabel>
					<Input
						placeholder={__('Your Section Name', 'masteriyo')}
						defaultValue={name}
						{...register('name', {
							required: __('Section name cannot be empty', 'masteriyo'),
						})}></Input>
					{errors?.name && (
						<FormErrorMessage>{errors?.name.message}</FormErrorMessage>
					)}
				</FormControl>
				<FormControl>
					<FormLabel htmlFor="">
						{__('Section Description', 'masteriyo')}
					</FormLabel>
					<Textarea
						defaultValue={description}
						rows={4}
						placeholder={__('short summary', 'masteriyo')}
						{...register('description')}
					/>
				</FormControl>
				<ButtonGroup>
					<Button colorScheme="blue" type="submit">
						{__('Save', 'masteriyo')}
					</Button>
					<Button variant="outline" onClick={() => onCancel()}>
						{__('Cancel', 'masteriyo')}
					</Button>
				</ButtonGroup>
			</Stack>
		</form>
	);
};

export default EditSection;
