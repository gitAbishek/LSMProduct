import {
	Box,
	Center,
	FormControl,
	FormLabel,
	Icon,
	Select,
	Spinner,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { useQuery } from 'react-query';
import ColorInput from '../../../components/common/ColorInput';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { GeneralSettingsMap } from '../../../types';
import PagesAPI from '../../../utils/pages';

interface Props {
	generalData?: GeneralSettingsMap;
}

const GeneralSettings: React.FC<Props> = (props) => {
	const { generalData } = props;
	const { register, setValue } = useFormContext();
	const pageAPI = new PagesAPI();
	const pagesQuery = useQuery('pages', () => pageAPI.list());

	const [primaryColor, setPrimaryColor] = useState(
		generalData?.styling?.primary_color || '#787DFF'
	);

	const renderPagesOption = () => {
		try {
			return pagesQuery?.data?.map(
				(page: { id: number; title: { rendered: string } }) => (
					<option value={page.id} key={page.id}>
						{page.title.rendered}
					</option>
				)
			);
		} catch (error) {
			return;
		}
	};

	useEffect(() => {
		setValue('general.styling.primary_color', primaryColor);
	}, [primaryColor, setValue]);

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Styling', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Pages', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<FormLabel>
									{__('Primary Color', 'masteriyo')}
									<Tooltip
										label={__(
											'Choose a color to match your brand or site. This color reflects on buttons, links, and few other elements.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<input
									type="hidden"
									{...register('general.styling.primary_color')}
									defaultValue={generalData?.styling?.primary_color}
								/>

								<ColorInput color={primaryColor} setColor={setPrimaryColor} />
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						{pagesQuery.isLoading ? (
							<Center h="20">
								<Spinner />
							</Center>
						) : (
							<Stack direction="column" spacing="8">
								<FormControl>
									<FormLabel>
										{__('Courses Page', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as courses page. This page will show all available courses.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										placeholder={__('Select a Page', 'masteriyo')}
										{...register('general.pages.courses_page_id')}
										defaultValue={generalData?.pages?.courses_page_id}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
										{__('Learn Page', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as learning page. This page runs the distraction free course learning page for any course.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										placeholder={__('Select a Page', 'masteriyo')}
										{...register('general.pages.learn_page_id')}
										defaultValue={generalData?.pages?.learn_page_id}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
										{__('Account Page', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as account page. This page shows the account of both student or instructor. The page should contain shortcode [masteriyo_account].',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										{...register('general.pages.account_page_id')}
										defaultValue={generalData?.pages?.account_page_id}
										placeholder={__('Select a Page', 'masteriyo')}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
										{__('Checkout Page', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as checkout page. This page shows the checkout page while purchasing any course. The page should contain shortcode [masteriyo_checkout].',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										placeholder={__('Select a Page', 'masteriyo')}
										defaultValue={generalData?.pages?.checkout_page_id}
										{...register('general.pages.checkout_page_id')}>
										{renderPagesOption()}
									</Select>
								</FormControl>

								<FormControl>
									<FormLabel>
										{__('Instructor Registration Page', 'masteriyo')}
										<Tooltip
											label={__(
												'Select a page to be set as instructor registration page. This page shows the signup form to apply as an instructor. The page should contain shortcode [masteriyo_instructor_registration].',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Select
										placeholder={__('Select a Page', 'masteriyo')}
										defaultValue={
											generalData?.pages?.instructor_registration_page_id
										}
										{...register(
											'general.pages.instructor_registration_page_id'
										)}>
										{renderPagesOption()}
									</Select>
								</FormControl>
							</Stack>
						)}
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default GeneralSettings;
