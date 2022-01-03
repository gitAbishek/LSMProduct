import {
	Box,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	FormLabel,
	Icon,
	Image,
	Spinner,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { useQuery } from 'react-query';
import MediaUploader from '../../../components/common/MediaUploader';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { MediaSchema } from '../../../schemas';
import { LearningPageSettingsMap } from '../../../types';
import MediaAPI from '../../../utils/media';

interface Props {
	learningPageData?: LearningPageSettingsMap;
}

const LearningPageSettings: React.FC<Props> = (props) => {
	const { learningPageData } = props;
	const { register, setValue } = useFormContext();
	const [imageId, setImageId] = useState<any>(
		learningPageData?.general?.logo_id || null
	);

	const imageAPi = new MediaAPI();

	const imageQuery = useQuery<MediaSchema>(
		[`learnPageLogo${imageId}`, imageId],
		() => imageAPi.get(imageId),
		{
			enabled: !!imageId,
			refetchOnWindowFocus: true,
		}
	);

	const onComplete = (imageId: number) => {
		setImageId(imageId);
		setValue('learn_page.general.logo_id', imageId);
	};

	const onDelete = () => {
		setImageId(null);
		setValue('learn_page.general.logo_id', 0);
	};

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<FormLabel>{__('Logo', 'masteriyo')}</FormLabel>
								{imageQuery.isLoading && (
									<Box w="40" mb="4">
										<Center>
											<Spinner />
										</Center>
									</Box>
								)}
								{imageQuery.isSuccess && (
									<Image
										w="40"
										src={
											imageQuery?.data?.media_details?.sizes?.['full']
												?.source_url
												? imageQuery?.data?.media_details?.sizes?.['full']
														?.source_url
												: imageQuery?.data?.source_url
										}
										mb="4"
									/>
								)}
								<ButtonGroup d="flex" justifyContent="space-between" w="40">
									{imageId && (
										<Button
											size="xs"
											variant="outline"
											onClick={onDelete}
											colorScheme="red">
											{__('Remove', 'masteriyo')}
										</Button>
									)}
									<MediaUploader
										size="xs"
										buttonLabel={
											imageId
												? __('Upload New', 'masteriyo')
												: __('Upload', 'masteriyo')
										}
										modalTitle="Featured Image"
										onSelect={(data: any) => {
											onComplete(data[0].id);
										}}
										isFullWidth={false}
									/>
								</ButtonGroup>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel>
										{__('Enable Questions & Answers', 'masteriyo')}
										<Tooltip
											label={__(
												'Display questions & answers tab in learn page.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Switch
										{...register('learn_page.display.enable_questions_answers')}
										defaultChecked={
											learningPageData?.display?.enable_questions_answers
										}
									/>
								</Stack>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default LearningPageSettings;
