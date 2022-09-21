import {
	BoxProps,
	FormControl,
	FormErrorMessage,
	Icon,
	IconButton,
	Input,
	Link,
	Stack,
	Tooltip,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiCheck, BiEdit, BiGlobe, BiX } from 'react-icons/bi';

interface Props extends BoxProps {
	permalink: string;
	slug: string;
}

const SlugEditor: React.FC<Props> = (props) => {
	const { permalink, slug } = props;
	const [newPermalink, setNewPermalink] = useState<string>(permalink);
	const { onOpen, isOpen, onClose } = useDisclosure();
	const {
		register,
		getValues,
		trigger,
		setFocus,
		formState: { errors },
	} = useFormContext();

	const onSlugEdit = () => {
		onOpen();
		setTimeout(() => {
			setFocus('slug');
		}, 200);
	};

	const onSlugChange = async () => {
		const result = await trigger('slug');
		if (result) {
			onClose();
			const slug = getValues('slug');
			setNewPermalink(newPermalink.replace(/course.*/, `course/${slug}`));
		}
	};

	return (
		<FormControl isInvalid={!!errors?.slug} w={'auto'}>
			<Stack direction={'row'} mb={'2'} h={'6'} align={'center'} spacing={'1'}>
				<Icon as={BiGlobe} color="gray.500" />
				{isOpen ? (
					<Stack direction={'row'} spacing={'1'} align={'center'}>
						<Link
							whiteSpace={'nowrap'}
							fontSize={'xs'}
							textDecor={'underline'}
							color="gray.500"
							lineHeight={'1'}>
							{newPermalink.replace(/course.*/, 'course/')}
						</Link>

						<Stack direction={'row'} spacing={'1'}>
							<Input
								defaultValue={slug}
								size={'xs'}
								height={'5'}
								fontSize="11px"
								{...register('slug', {
									required: __('This field cannot be empty', 'masteriyo'),
									pattern: {
										value: /^\S*$/,
										message: __('Spaces are not allowed', 'masteriyo'),
									},
								})}
							/>
							<IconButton
								w={'auto'}
								minW={'auto'}
								h={'5'}
								lineHeight={'1'}
								minH={'auto'}
								variant={'outline'}
								colorScheme={'green'}
								size={'lg'}
								icon={<BiCheck />}
								aria-label={__('Save', 'masteriyo')}
								onClick={onSlugChange}
							/>
							<IconButton
								w={'auto'}
								minW={'auto'}
								h={'5'}
								lineHeight={'1'}
								minH={'auto'}
								variant={'outline'}
								colorScheme={'red'}
								size={'lg'}
								icon={<BiX />}
								aria-label={__('Close', 'masteriyo')}
								onClick={onClose}
							/>
						</Stack>
					</Stack>
				) : (
					<>
						<Link
							href={newPermalink}
							isExternal
							fontSize={'xs'}
							textDecor={'underline'}
							color="gray.500"
							lineHeight={'1'}>
							{newPermalink}
						</Link>
						<Tooltip
							label={__('Edit slug for permalink', 'masteriyo')}
							hasArrow>
							<IconButton
								w={'auto'}
								minW={'auto'}
								lineHeight={'1'}
								minH={'auto'}
								variant={'unstyled'}
								color={'gray.500'}
								_hover={{
									color: 'blue.700',
								}}
								aria-label={__('Edit Slug', 'masteriyo')}
								icon={<BiEdit />}
								onClick={onSlugEdit}
							/>
						</Tooltip>
					</>
				)}
			</Stack>
			{errors?.slug && (
				<FormErrorMessage fontSize={'xs'}>
					{errors?.slug?.message}
				</FormErrorMessage>
			)}
		</FormControl>
	);
};

export default SlugEditor;
