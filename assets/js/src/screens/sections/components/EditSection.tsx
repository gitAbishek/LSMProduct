import {
	Button,
	ButtonGroup,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Spacer,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { Editor } from '@tinymce/tinymce-react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useForm } from 'react-hook-form';
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
		control,
		formState: { errors },
	} = useForm<SectionInputs>();
	const queryClient = useQueryClient();
	const toast = useToast();
	const sectionAPI = new API(urls.sections);

	const updateMutation = useMutation(
		(data: any) => sectionAPI.update(id, data),
		{
			onSuccess: (data: any) => {
				console.log(data);
				toast({
					title: __('Updated Successfully', 'masteriyo'),
					description: data.name + __(' is updated succesffuly', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries('builderSections');
				onSave();
			},
		}
	);

	const onUpdate = (data: any) => {
		console.log(data);
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
					<Controller
						name="description"
						control={control}
						render={({ field: { value, onChange } }) => (
							<Editor
								initialValue={description}
								tinymceScriptSrc="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.7.1/tinymce.min.js"
								onEditorChange={onChange}
							/>
						)}
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
			<Spacer h="8" />
		</form>
	);
};

export default EditSection;
