import {
	Button,
	ButtonGroup,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef } from 'react';
import { useForm } from 'react-hook-form';

type IFormInputs = {
	password: string | number;
	newPassword: string | number;
	confirmPassword: string | number;
};

const PasswordSecurity = () => {
	const {
		register,
		handleSubmit,
		watch,
		formState: { errors },
	} = useForm<IFormInputs>();
	const password = useRef({});
	password.current = watch('password', '');
	const onSubmit = handleSubmit(() => {});

	return (
		<form onSubmit={onSubmit}>
			<Stack spacing="9" w="md">
				<Heading as="h4" size="lg" fontWeight="medium">
					Change Password
				</Heading>
				<Stack direction="column" spacing="8">
					<FormControl isInvalid={!!errors?.password}>
						<FormLabel>{__('Current Password', 'masteriyo')}</FormLabel>
						<Input
							type="password"
							{...register('password', {
								required: __('This field cannot be empty', 'masteriyo'),
								minLength: {
									value: 8,
									message: 'Password must have at least 8 characters',
								},
							})}
						/>
						{errors?.password && (
							<FormErrorMessage>{errors?.password.message}</FormErrorMessage>
						)}
					</FormControl>
					<FormControl isInvalid={!!errors?.newPassword}>
						<FormLabel>{__('New Password', 'masteriyo')}</FormLabel>
						<Input
							type="password"
							{...register('newPassword', {
								required: __('This field cannot be empty', 'masteriyo'),
								minLength: {
									value: 8,
									message: 'Password must have at least 8 characters',
								},
							})}
						/>
						{errors?.newPassword && (
							<FormErrorMessage>{errors?.newPassword.message}</FormErrorMessage>
						)}
					</FormControl>
					<FormControl isInvalid={!!errors?.confirmPassword}>
						<FormLabel>{__('Confirm New Password', 'masteriyo')}</FormLabel>
						<Input
							type="password"
							{...register('confirmPassword', {
								required: __('This field cannot be empty', 'masteriyo'),
								validate: (value) =>
									value === password.current ||
									__('The passwords do not match'),
							})}
						/>
						{errors?.confirmPassword && (
							<FormErrorMessage>
								{errors?.confirmPassword.message}
							</FormErrorMessage>
						)}
					</FormControl>
				</Stack>
			</Stack>

			<Stack py="10">
				<ButtonGroup>
					<Button
						colorScheme="blue"
						rounded="full"
						type="submit"
						px="19"
						textTransform="uppercase">
						{__('Change Password', 'masteriyo')}
					</Button>
				</ButtonGroup>
			</Stack>
		</form>
	);
};

export default PasswordSecurity;
